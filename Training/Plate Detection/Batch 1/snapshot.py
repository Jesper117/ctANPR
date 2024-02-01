import os
from PIL import Image


def parse_label(label_path):
    with open(label_path, 'r') as label_file:
        lines = label_file.readlines()
        crops = []
        for line in lines:
            parts = line.strip().split()
            class_id = int(parts[0])
            x_center = float(parts[1])
            y_center = float(parts[2])
            width = float(parts[3])
            height = float(parts[4])
            crops.append((class_id, x_center, y_center, width, height))
        return crops


def main():
    image_folder = 'images'
    label_folder = 'labels'
    output_folder = 'snapshots'

    if not os.path.exists(output_folder):
        os.makedirs(output_folder)

    image_files = [f for f in os.listdir(image_folder) if f.endswith('.png')]

    for image_file in image_files:
        image_path = os.path.join(image_folder, image_file)
        label_file = image_file.replace('.png', '.txt')
        label_path = os.path.join(label_folder, label_file)

        if os.path.exists(label_path):
            crops = parse_label(label_path)

            # Open the image
            image = Image.open(image_path)
            image.show()

            # Prompt user for snapshot name
            snapshot_name = input(f"Enter a name for the snapshot of '{image_file}': ")

            if not snapshot_name:
                print("Snapshot skipped.")
                continue

            for idx, crop in enumerate(crops):
                class_id, x_center, y_center, width, height = crop
                img_width, img_height = image.size

                x_min = int((x_center - width / 2) * img_width)
                y_min = int((y_center - height / 2) * img_height)
                x_max = int((x_center + width / 2) * img_width)
                y_max = int((y_center + height / 2) * img_height)

                cropped_img = image.crop((x_min, y_min, x_max, y_max))
                snapshot_path = os.path.join(output_folder, f'{snapshot_name}.png')
                cropped_img.save(snapshot_path)

                print(f'Saved snapshot: {snapshot_path}')
        else:
            print(f'Label not found for {image_file}, skipping.')


if __name__ == "__main__":
    main()
