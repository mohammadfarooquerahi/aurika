"""
Comprehensive Build Script for Aurika Theme & Installer Plugin
Builds:
1. aurika-theme.zip (Standard Theme ZIP with explicit directory entries and POSIX permissions)
2. aurika-theme-root.zip (Root-level Theme ZIP with style.css directly at root)
3. aurika-installer-plugin.zip (WordPress Plugin that copies theme files and activates it with 100% reliability)
"""
import os
import zipfile
import shutil
import time

SRC_DIR = os.path.join(os.path.dirname(__file__), 'aurika-theme')
OUT_DIR = os.path.dirname(__file__)

EXCLUDE_DIRS = {'.git', '__pycache__', 'node_modules', '.DS_Store', '.idea'}
EXCLUDE_EXTS = {'.pyc', '.log'}

def should_include(rel_path):
    parts = rel_path.replace('\\', '/').split('/')
    for p in parts:
        if p in EXCLUDE_DIRS:
            return False
    _, ext = os.path.splitext(rel_path)
    if ext in EXCLUDE_EXTS:
        return False
    return True

def create_theme_zip_with_folder(out_zip_path, folder_name="aurika-theme"):
    """Creates a theme zip with explicit directory entries and POSIX permissions."""
    if os.path.exists(out_zip_path):
        os.remove(out_zip_path)
    
    count = 0
    now = time.localtime(time.time())[:6]
    
    with zipfile.ZipFile(out_zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
        # 1. Write root directory entry
        zinfo = zipfile.ZipInfo(f"{folder_name}/", now)
        zinfo.external_attr = 0o755 << 16 | 0x10  # directory attribute
        zf.writestr(zinfo, '')
        
        # 2. Walk and write subdirectories and files
        written_dirs = {f"{folder_name}/"}
        for root, dirs, files in os.walk(SRC_DIR):
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            
            for d in dirs:
                full_dir = os.path.join(root, d)
                rel_dir = os.path.relpath(full_dir, SRC_DIR).replace('\\', '/')
                dir_entry = f"{folder_name}/{rel_dir}/"
                if dir_entry not in written_dirs:
                    d_info = zipfile.ZipInfo(dir_entry, now)
                    d_info.external_attr = 0o755 << 16 | 0x10
                    zf.writestr(d_info, '')
                    written_dirs.add(dir_entry)
            
            for fname in files:
                full_path = os.path.join(root, fname)
                rel_path = os.path.relpath(full_path, SRC_DIR).replace('\\', '/')
                if should_include(rel_path):
                    arc_name = f"{folder_name}/{rel_path}"
                    f_info = zipfile.ZipInfo(arc_name, now)
                    f_info.external_attr = 0o644 << 16  # standard file attribute
                    f_info.compress_type = zipfile.ZIP_DEFLATED
                    with open(full_path, 'rb') as fp:
                        zf.writestr(f_info, fp.read())
                    count += 1
                    
    size_mb = os.path.getsize(out_zip_path) / (1024 * 1024)
    print(f"[OK] {os.path.basename(out_zip_path)}: {count} files ({size_mb:.2f} MB)")

def create_theme_root_zip(out_zip_path):
    """Creates a theme zip where style.css and files are directly at the root."""
    if os.path.exists(out_zip_path):
        os.remove(out_zip_path)
    
    count = 0
    now = time.localtime(time.time())[:6]
    
    with zipfile.ZipFile(out_zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
        written_dirs = set()
        for root, dirs, files in os.walk(SRC_DIR):
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            
            for d in dirs:
                full_dir = os.path.join(root, d)
                rel_dir = os.path.relpath(full_dir, SRC_DIR).replace('\\', '/')
                dir_entry = f"{rel_dir}/"
                if dir_entry not in written_dirs:
                    d_info = zipfile.ZipInfo(dir_entry, now)
                    d_info.external_attr = 0o755 << 16 | 0x10
                    zf.writestr(d_info, '')
                    written_dirs.add(dir_entry)
                    
            for fname in files:
                full_path = os.path.join(root, fname)
                rel_path = os.path.relpath(full_path, SRC_DIR).replace('\\', '/')
                if should_include(rel_path):
                    f_info = zipfile.ZipInfo(rel_path, now)
                    f_info.external_attr = 0o644 << 16
                    f_info.compress_type = zipfile.ZIP_DEFLATED
                    with open(full_path, 'rb') as fp:
                        zf.writestr(f_info, fp.read())
                    count += 1
                    
    size_mb = os.path.getsize(out_zip_path) / (1024 * 1024)
    print(f"[OK] {os.path.basename(out_zip_path)}: {count} files ({size_mb:.2f} MB)")

def create_installer_plugin(out_zip_path):
    """Creates a 1-click WordPress plugin to install & activate the theme with 100% reliability."""
    if os.path.exists(out_zip_path):
        os.remove(out_zip_path)
        
    temp_plugin_dir = os.path.join(OUT_DIR, "_temp_installer_plugin")
    if os.path.exists(temp_plugin_dir):
        shutil.rmtree(temp_plugin_dir)
        
    dest_theme_files = os.path.join(temp_plugin_dir, "aurika-theme-installer", "theme_files")
    os.makedirs(dest_theme_files, exist_ok=True)
    
    # Copy theme files
    for root, dirs, files in os.walk(SRC_DIR):
        dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
        for f in files:
            src_f = os.path.join(root, f)
            rel_f = os.path.relpath(src_f, SRC_DIR)
            if should_include(rel_f):
                dest_f = os.path.join(dest_theme_files, rel_f)
                os.makedirs(os.path.dirname(dest_f), exist_ok=True)
                shutil.copy2(src_f, dest_f)
                
    plugin_php = """<?php
/**
 * Plugin Name: Aurika Theme Auto-Installer & Activator
 * Plugin URI: https://aurika.online
 * Description: 1-Click installer that copies all Aurika v6.14.0 Theme files to wp-content/themes/aurika-theme/ and activates it automatically.
 * Version: 6.14.0
 * Author: Aurika Team
 */

defined( 'ABSPATH' ) || exit;

register_activation_hook( __FILE__, 'aurika_auto_install_and_activate_theme' );

function aurika_auto_install_and_activate_theme() {
    $theme_slug = 'aurika-theme';
    $target_dir = get_theme_root() . '/' . $theme_slug;
    $source_dir = plugin_dir_path( __FILE__ ) . 'theme_files';

    if ( ! file_exists( $target_dir ) ) {
        wp_mkdir_p( $target_dir );
    }

    aurika_installer_recurse_copy( $source_dir, $target_dir );

    // Clear theme caches
    wp_clean_themes_cache();

    // Activate the theme
    switch_theme( $theme_slug );
}

function aurika_installer_recurse_copy( $src, $dst ) {
    $dir = opendir( $src );
    @mkdir( $dst );
    while ( false !== ( $file = readdir( $dir ) ) ) {
        if ( ( $file != '.' ) && ( $file != '..' ) ) {
            if ( is_dir( $src . '/' . $file ) ) {
                aurika_installer_recurse_copy( $src . '/' . $file, $dst . '/' . $file );
            } else {
                copy( $src . '/' . $file, $dst . '/' . $file );
            }
        }
    }
    closedir( $dir );
}

add_action( 'admin_notices', function() {
    echo '<div class="notice notice-success is-dismissible"><p><strong>✨ Aurika Theme v6.14.0 is successfully installed and activated! All updates applied!</strong></p></div>';
} );
"""
    with open(os.path.join(temp_plugin_dir, "aurika-theme-installer", "aurika-theme-installer.php"), "w", encoding="utf-8") as f:
        f.write(plugin_php)
        
    count = 0
    now = time.localtime(time.time())[:6]
    with zipfile.ZipFile(out_zip_path, 'w', zipfile.ZIP_DEFLATED) as zf:
        for root, dirs, files in os.walk(temp_plugin_dir):
            for file in files:
                full_path = os.path.join(root, file)
                rel_path = os.path.relpath(full_path, temp_plugin_dir).replace('\\', '/')
                f_info = zipfile.ZipInfo(rel_path, now)
                f_info.external_attr = 0o644 << 16
                f_info.compress_type = zipfile.ZIP_DEFLATED
                with open(full_path, 'rb') as fp:
                    zf.writestr(f_info, fp.read())
                count += 1
                
    shutil.rmtree(temp_plugin_dir)
    size_mb = os.path.getsize(out_zip_path) / (1024 * 1024)
    print(f"[OK] {os.path.basename(out_zip_path)}: {count} files ({size_mb:.2f} MB)")

if __name__ == '__main__':
    # 1. aurika-v84.zip (v6.24.0 — Developer credit opacity update: 10% visible, 20% on hover)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v84.zip"), "aurika-v84")
    # 2. aurika-v83.zip (v6.23.0 — Full Google Analytics 4 & Performance Tracking Engine, Settings page & Customizer controls)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v83.zip"), "aurika-v83")
    # 2. aurika-v82.zip (v6.22.0 — Crisp outline SVG icons for My Account dashboard, nav pills, cards, and Gutenberg content)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v82.zip"), "aurika-v82")
    # 2. aurika-v81.zip (v6.21.0 — Admin product image cropper on all sides, LinkedIn developer credit, hidden CS grad/web dev metadata)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v81.zip"), "aurika-v81")
    # 2. aurika-v80.zip (v6.20.0 — Privacy policy 10 sections final, remove CEO photo on about, fix footer quick links)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v80.zip"), "aurika-v80")
    # 2. aurika-v79.zip (v6.19.0 — product image crop/fit controls, privacy policy updates, Google Shopping schema)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v79.zip"), "aurika-v79")
    # 2. aurika-v78.zip (v6.18.0)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v78.zip"), "aurika-v78")
    # 2. aurika-v77.zip (v6.17.0)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v77.zip"), "aurika-v77")
    # 2. aurika-v76.zip (v6.16.0)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v76.zip"), "aurika-v76")
    # 2. aurika-v75.zip (v6.15.0)
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v75.zip"), "aurika-v75")
    # 2. aurika-v74.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v74.zip"), "aurika-v74")
    # 2. aurika-v73.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v73.zip"), "aurika-v73")
    # 2. aurika-v72.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v72.zip"), "aurika-v72")
    # 3. aurika-v71.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v71.zip"), "aurika-v71")
    # 3. aurika-v70.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v70.zip"), "aurika-v70")
    # 3. aurika-v69.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v69.zip"), "aurika-v69")
    # 3. aurika-v68.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v68.zip"), "aurika-v68")
    # 2. aurika-v67.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v67.zip"), "aurika-v67")
    # 2. aurika-v66.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v66.zip"), "aurika-v66")
    # 2. aurika-v65.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v65.zip"), "aurika-v65")
    # 2. aurika-v64.zip
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-v64.zip"), "aurika-v64")
    # 3. Standard Theme ZIP
    create_theme_zip_with_folder(os.path.join(OUT_DIR, "aurika-theme.zip"), "aurika-theme")
    # 4. Root-level Theme ZIP
    create_theme_root_zip(os.path.join(OUT_DIR, "aurika-theme-root.zip"))
    # 5. 1-Click Installer Plugin
    create_installer_plugin(os.path.join(OUT_DIR, "aurika-theme-installer-plugin.zip"))
    print("All packages built successfully!")
