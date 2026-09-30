"""
Build aurika-theme.zip for WordPress upload.
Creates a properly structured ZIP with aurika-theme/ as root folder.
"""
import os
import zipfile

SRC_DIR = os.path.join(os.path.dirname(__file__), 'aurika-theme')
OUT_ZIP = os.path.join(os.path.dirname(__file__), 'aurika-theme.zip')
FOLDER_NAME = 'aurika-theme'

# Extensions/folders to exclude from the ZIP
EXCLUDE_DIRS = {'.git', '__pycache__', 'node_modules', '.DS_Store'}
EXCLUDE_EXTS = {'.pyc', '.log'}

def should_include(path):
    parts = path.replace('\\', '/').split('/')
    for part in parts:
        if part in EXCLUDE_DIRS:
            return False
    _, ext = os.path.splitext(path)
    if ext in EXCLUDE_EXTS:
        return False
    return True

def build_zip():
    if os.path.exists(OUT_ZIP):
        os.remove(OUT_ZIP)

    count = 0
    with zipfile.ZipFile(OUT_ZIP, 'w', zipfile.ZIP_DEFLATED) as zf:
        for root, dirs, files in os.walk(SRC_DIR):
            # Filter out excluded directories
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            for fname in files:
                full_path = os.path.join(root, fname)
                rel_path = os.path.relpath(full_path, SRC_DIR)
                if should_include(rel_path):
                    # arcname = aurika-theme/relative/path
                    arc_name = os.path.join(FOLDER_NAME, rel_path).replace('\\', '/')
                    zf.write(full_path, arc_name)
                    count += 1

    size_mb = os.path.getsize(OUT_ZIP) / (1024 * 1024)
    print(f"[OK] Created: {OUT_ZIP}")
    print(f"   Files: {count}")
    print(f"   Size: {size_mb:.2f} MB")

    # Verify style.css is at the correct path
    with zipfile.ZipFile(OUT_ZIP, 'r') as zf:
        names = zf.namelist()
        style_path = f"{FOLDER_NAME}/style.css"
        if style_path in names:
            print(f"   [OK] {style_path} found at correct path")
        else:
            print(f"   [ERROR] WARNING: {style_path} NOT found!")
            print(f"   First 10 entries: {names[:10]}")

if __name__ == '__main__':
    build_zip()
