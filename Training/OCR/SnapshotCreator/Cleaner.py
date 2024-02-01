import os
import shutil
from PIL import Image
import uuid

source_folder = "Snapshots"
archive_folder = "Archive"
min_horizontal_pixels = 40

error_log = "error_log.txt"

for filename in os.listdir(source_folder):
    if filename.endswith((".jpg", ".jpeg")):
        image_path = os.path.join(source_folder, filename)

        try:
            img = Image.open(image_path)
            width, _ = img.size
            img.close()  # Close the image explicitly

            if width < min_horizontal_pixels:
                shutil.move(image_path, os.path.join(archive_folder, filename))
            else:
                new_filename = f"{str(uuid.uuid4())}.jpg"
                os.rename(image_path, os.path.join(source_folder, new_filename))
        except Exception as e:
            print(f"Error: {e}")
            with open(error_log, "a") as f:
                f.write(f"Error: {e}\n")