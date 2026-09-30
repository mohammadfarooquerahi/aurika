import zipfile
import os

source_dir = r"C:\Users\DC\BIC data\Aurika\aurika-theme"
output_dir = r"C:\Users\DC\BIC data\Aurika"

targets = [
    ("aurika-store.zip", "aurika-store"),
    ("aurika-theme.zip", "aurika-theme"),
    ("aurika-final.zip", "aurika-final")
]

for zip_filename, folder_name in targets:
    zip_path = os.path.join(output_dir, zip_filename)
    if os.path.exists(zip_path):
        os.remove(zip_path)
    
    count = 0
    with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
        for root, dirs, files in os.walk(source_dir):
            for file in files:
                file_path = os.path.join(root, file)
                rel_path = os.path.relpath(file_path, source_dir).replace('\\', '/')
                archive_name = f"{folder_name}/{rel_path}"
                zf.write(file_path, archive_name)
                count += 1
                
    size_mb = os.path.getsize(zip_path) / 1024 / 1024
    print(f"Created: {zip_filename} | Folder: {folder_name}/ | Files: {count} | Size: {size_mb:.2f} MB")

# Verify all created zips
print("\n--- Verification ---")
for zip_filename, folder_name in targets:
    zip_path = os.path.join(output_dir, zip_filename)
    with zipfile.ZipFile(zip_path, 'r') as zf:
        names = zf.namelist()
        style_entry = f"{folder_name}/style.css"
        func_entry = f"{folder_name}/functions.php"
        has_style = style_entry in names
        has_func = func_entry in names
        print(f"{zip_filename}: style.css={'OK' if has_style else 'FAIL'}, functions.php={'OK' if has_func else 'FAIL'}")
