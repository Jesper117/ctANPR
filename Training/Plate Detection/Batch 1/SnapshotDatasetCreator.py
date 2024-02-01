import os

# Path to the snapshots folder and snapshotlabels folder
snapshots_folder = "snapshots"
labels_folder = "snapshotlabels"

# Create snapshotlabels folder if it doesn't exist
if not os.path.exists(labels_folder):
    os.makedirs(labels_folder)

# Get a list of all files in the snapshots folder
snapshot_files = os.listdir(snapshots_folder)

# Loop through the snapshot files
for index, snapshot_file in enumerate(snapshot_files):
    # Check if the file is a PNG image
    if snapshot_file.lower().endswith(".png"):
        # Get the original name (excluding .png)
        original_name = os.path.splitext(snapshot_file)[0]

        # Create a new .txt file in snapshotlabels folder
        label_file_name = os.path.join(labels_folder, f"{index}.txt")
        with open(label_file_name, "w") as label_file:
            label_file.write(original_name)

        # Rename both .txt and .png files
        new_image_name = os.path.join(snapshots_folder, f"{index}.png")
        os.rename(os.path.join(snapshots_folder, snapshot_file), new_image_name)
        os.rename(label_file_name, os.path.join(labels_folder, f"{index}.txt"))
