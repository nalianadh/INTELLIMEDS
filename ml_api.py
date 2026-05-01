# ================================================================
# 1. IMPORT LIBRARIES
# ================================================================
import pickle
import pandas as pd
import numpy as np
from fastapi import FastAPI
from pydantic import BaseModel
from sqlalchemy import create_engine

# ================================================================
# 2. LOAD TRAINED MODEL & MAPPINGS
# ================================================================
with open("rf_model.pkl", "rb") as f:
    model = pickle.load(f)

with open("encoders.pkl", "rb") as f:
    encoders = pickle.load(f)

with open("label_mapping.pkl", "rb") as f:
    label_mapping = pickle.load(f)

# ================================================================
# 3. DATABASE CONNECTION
# ================================================================
#DB_USERNAME = "root"
#DB_PASSWORD = "Alia160203@2"
#DB_HOST = "127.0.0.1"
#DB_NAME = "fyp_projectdb"

#engine = create_engine(
    #f"mysql+pymysql://{DB_USERNAME}:{DB_PASSWORD}@{DB_HOST}/{DB_NAME}"
#)
import os

DB_USERNAME = os.getenv("DB_USERNAME")
DB_PASSWORD = os.getenv("DB_PASSWORD")
DB_HOST = os.getenv("DB_HOST")
DB_NAME = os.getenv("DB_NAME")

engine = create_engine(
    f"mysql+pymysql://{DB_USERNAME}:{DB_PASSWORD}@{DB_HOST}/{DB_NAME}"
)

# ================================================================
# 4. FEATURE LIST
# ================================================================
FEATURES = [
    "Stock",
    "Brand",
    "Site_Supplier",
    "Activity",
    "Quantity",
    "Unit",
    "Year",
    "Month"
]

# ================================================================
# 5. LOAD DATA FROM DATABASE
# ================================================================
def load_supply_data():
    query = "SELECT * FROM supply_transaction"
    df = pd.read_sql(query, engine)

    df["Date"] = pd.to_datetime(df["Date"], errors="coerce")
    df["Year"] = df["Date"].dt.year
    df["Month"] = df["Date"].dt.month

    return df[FEATURES].dropna()

# ================================================================
# 6. SAFE ENCODING FUNCTION
# ================================================================
def safe_encode(df):
    df_encoded = df.copy()
    for col in df_encoded.columns:
        if col in encoders:
            encoder = encoders[col]
            # Replace unknown values with 'Unknown'
            df_encoded[col] = df_encoded[col].apply(lambda x: x if x in encoder.classes_ else "Unknown")
            # Add 'Unknown' to encoder classes if not present
            if "Unknown" not in encoder.classes_:
                encoder.classes_ = np.append(encoder.classes_, "Unknown")
            df_encoded[col] = encoder.transform(df_encoded[col])
    return df_encoded

# ================================================================
# 7. PREDICTION FUNCTION FOR BATCH DATA
# ================================================================
def predict_batch(df):
    df_encoded = safe_encode(df)

    predictions = model.predict(df_encoded)

    df["Predicted_Demand"] = [label_mapping.get(int(p), "Unknown") for p in predictions]

    return df

# ================================================================
# 8. FASTAPI APP
# ================================================================
app = FastAPI(title="INTELLIMEDS ML API")

# ================================================================
# 9. REQUEST MODEL
# ================================================================
class PredictionRequest(BaseModel):
    stock: str
    brand: str
    site_supplier: str
    activity: str
    quantity: int
    unit: str
    year: int
    month: int

# ================================================================
# 10. SINGLE PREDICTION ENDPOINT
# ================================================================
@app.post("/predict")
def predict_demand(data: PredictionRequest):
    input_df = pd.DataFrame([{
        "Stock": data.stock,
        "Brand": data.brand,
        "Site_Supplier": data.site_supplier,
        "Activity": data.activity,
        "Quantity": data.quantity,
        "Unit": data.unit,
        "Year": data.year,
        "Month": data.month
    }])

    # Safe encoding
    input_encoded = safe_encode(input_df)

    # Predict
    prediction = model.predict(input_encoded)[0]

    # Map to label
    demand_label = label_mapping.get(int(prediction), "Unknown")

    return {"predicted_demand": demand_label}

# ================================================================
# 11. BATCH PREDICTION ENDPOINT
# ================================================================
@app.get("/predict_all")
def predict_all_supply():
    df = load_supply_data()
    df_pred = predict_batch(df)

    df_pred.to_sql(
        "supply_predictions",
        engine,
        if_exists="replace",
        index=False
    )

    return {
        "status": "success",
        "records_predicted": len(df_pred)
    }

# ================================================================
# 12. HEALTH CHECK
# ================================================================
@app.get("/")
def root():
    return {"status": "INTELLIMEDS ML API is running"}
