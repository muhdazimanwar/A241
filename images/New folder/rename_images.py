import os

def rename_images(directory_path):
    try:
        # Get a list of all files in the directory
        files = [file for file in os.listdir(directory_path) if os.path.isfile(os.path.join(directory_path, file))]

        # Filter image files (optional: adjust extensions as needed)
        image_extensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp']
        image_files = [file for file in files if os.path.splitext(file)[1].lower() in image_extensions]

        # Sort the image files alphabetically (optional)
        image_files.sort()

        # Rename the image files
        for index, file in enumerate(image_files, start=1):
            old_file_path = os.path.join(directory_path, file)
            new_file_name = f"product{index}{os.path.splitext(file)[1].lower()}"
            new_file_path = os.path.join(directory_path, new_file_name)

            os.rename(old_file_path, new_file_path)
            print(f"Renamed: {file} -> {new_file_name}")

        print("All files have been renamed successfully.")

    except Exception as e:
        print(f"An error occurred: {e}")

# Specify the directory containing the images
directory_path = r"C:\xampp\htdocs\myevent\images"
rename_images(directory_path)
