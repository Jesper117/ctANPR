import os

# Read the classes from classes.txt into a list
with open('classes.txt', 'r') as classes_file:
    classes = [line.strip() for line in classes_file]

# Define a function to map class names to class indices
def class_to_index(class_name):
    return classes.index(class_name)

# Directory where your YOLOv8 label files are located
label_directory = 'labels'

# Loop through the label files in the directory
for label_file_name in os.listdir(label_directory):
    if label_file_name.endswith('.txt'):
        label_file_path = os.path.join(label_directory, label_file_name)

        if os.stat(label_file_path).st_size == 0:
            os.remove(label_file_path)
            os.remove(os.path.join('images', label_file_name.replace('.txt', '.png')))
            print(f'Removed {label_file_name} and its corresponding image file because it wasn\'t annotated')
            continue
        else:
            with open(label_file_path, 'r') as label_file:
                converted_labels = []
                for line in label_file:
                    parts = line.split()
                    class_name = parts[0]
                    class_index = class_to_index(class_name)
                    parts[0] = str(class_index)
                    converted_labels.append(' '.join(parts))

        # Save the converted labels back to the file
        with open(label_file_path, 'w') as label_file:
            label_file.writelines('\n'.join(converted_labels))
