import pandas as pd
import mysql.connector
import os

# Database connection parameters
db_config = {
    'user': 'root',
    'password': '',
    'host': '127.0.0.1',
    'database': 'ctanpr',
}

# CSV file path
csv_file = 'export.csv'

# Load the CSV file into a DataFrame
print("Loading CSV file into a DataFrame...")
df = pd.read_csv(csv_file)
print(f"Loaded {len(df)} rows.")

# Establish a database connection
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

# Prepare the SQL INSERT statement
insert_query = f"INSERT INTO vehicles ({', '.join(df.columns)}) VALUES ({', '.join(['%s'] * len(df.columns))})"

# Set the batch size for progress updates
batch_size = 1000
row_count = 0

# Insert the data into the database
for row in df.itertuples(index=False, name=None):
    cursor.execute(insert_query, row)
    row_count += 1

    # Print progress and clear the output every batch_size rows
    if row_count % batch_size == 0:
        os.system('cls' if os.name == 'nt' else 'clear')  # Clear the output
        print(f"Inserted {row_count} rows.")

# Commit the changes and close the database connection
conn.commit()
conn.close()

print("CSV data has been successfully imported into the database.")
