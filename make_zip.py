import zipfile
import os

source_dir  = r"C:\Users\DC\BIC data\Aurika\aurika-theme"
folder_name = "aurika-store"
zip_path    = r"C:\Users\DC\BIC data\Aurika\aurika-store.zip"

# Remove old ZIP
if os.path.exists(zip_path):
    os.remove(zip_path)

count = 0
with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(source_dir):
        for file in files:
            file_path    = os.path.join(root, file)
            # Build forward-slash relative path inside aurika-store/
            rel_path     = os.path.relpath(file_path, source_dir).replace("\\", "/")
            archive_name = f"{folder_name}/{rel_path}"
            zf.write(file_path, archive_name)
            count += 1

size_mb = os.path.getsize(zip_path) / 1024 / 1024
print(f"ZIP created: {zip_path}")
print(f"Files packed: {count}")
print(f"Size: {size_mb:.2f} MB")

# Verify key files
print("\n--- Verifying key entries ---")
with zipfile.ZipFile(zip_path, 'r') as zf:
    names = zf.namelist()
    for check in ["aurika-store/style.css", "aurika-store/functions.php", 
                  "aurika-store/header.php", "aurika-store/assets/css/main.css"]:
        status = "FOUND" if check in names else "MISSING"
        print(f"{status}: {check}")

    # Read style.css header from ZIP
    print("\n--- style.css header ---")
    with zf.open("aurika-store/style.css") as f:
        content = f.read(300).decode('utf-8', errors='replace')
        print(content[:300])
