import os
import zipfile
import shutil

theme_src = r"C:\Users\DC\BIC data\Aurika\aurika-theme"
output_dir = r"C:\Users\DC\BIC data\Aurika"

# 1. Build aurika-v57.zip (Standard Theme ZIP)
v57_zip = os.path.join(output_dir, "aurika-v57.zip")
if os.path.exists(v57_zip):
    os.remove(v57_zip)

count = 0
with zipfile.ZipFile(v57_zip, 'w', zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(theme_src):
        for file in files:
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, theme_src).replace('\\', '/')
            entry_name = f"aurika-v57/{rel_path}"
            zf.write(full_path, entry_name)
            count += 1

print(f"1. aurika-v57.zip created with {count} files ({os.path.getsize(v57_zip)/1024/1024:.2f} MB)")

# 2. Build aurika-final.zip (to overwrite existing server theme folder aurika-final if needed)
final_zip = os.path.join(output_dir, "aurika-final.zip")
if os.path.exists(final_zip):
    os.remove(final_zip)

count = 0
with zipfile.ZipFile(final_zip, 'w', zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(theme_src):
        for file in files:
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, theme_src).replace('\\', '/')
            entry_name = f"aurika-final/{rel_path}"
            zf.write(full_path, entry_name)
            count += 1

print(f"2. aurika-final.zip created with {count} files ({os.path.getsize(final_zip)/1024/1024:.2f} MB)")

# 3. Build aurika-theme-installer-plugin.zip (WordPress Plugin that installs the theme)
plugin_dir = os.path.join(output_dir, "_installer_plugin_temp")
if os.path.exists(plugin_dir):
    shutil.rmtree(plugin_dir)
os.makedirs(os.path.join(plugin_dir, "aurika-theme-installer", "theme_files"), exist_ok=True)

# Copy all theme files into plugin's theme_files
for root, dirs, files in os.walk(theme_src):
    for file in files:
        src_f = os.path.join(root, file)
        rel_f = os.path.relpath(src_f, theme_src)
        dest_f = os.path.join(plugin_dir, "aurika-theme-installer", "theme_files", rel_f)
        os.makedirs(os.path.dirname(dest_f), exist_ok=True)
        shutil.copy2(src_f, dest_f)

# Create plugin main PHP file
plugin_php = """<?php
/**
 * Plugin Name: Aurika Theme Auto-Installer & Activator
 * Plugin URI: https://aurika.online
 * Description: 1-Click installer that copies all Aurika v5.7 Theme files to wp-content/themes/aurika-v57/ and activates it automatically.
 * Version: 5.7.0
 * Author: Aurika Team
 */

defined( 'ABSPATH' ) || exit;

register_activation_hook( __FILE__, 'aurika_auto_install_and_activate_theme' );

function aurika_auto_install_and_activate_theme() {
    $theme_slug = 'aurika-v57';
    $target_dir = get_theme_root() . '/' . $theme_slug;
    $source_dir = plugin_dir_path( __FILE__ ) . 'theme_files';

    if ( ! file_exists( $target_dir ) ) {
        wp_mkdir_p( $target_dir );
    }

    // Recursive copy
    aurika_installer_recurse_copy( $source_dir, $target_dir );

    // Switch active theme
    switch_theme( $theme_slug );
}

function aurika_installer_recurse_copy( $src, $dst ) {
    $dir = opendir( $src );
    @mkdir( $dst, 0755, true );
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
    if ( get_stylesheet() === 'aurika-v57' ) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>✨ Aurika Theme v5.7 is successfully installed and activated! Checkout and home footers are now 100% identical!</strong></p></div>';
    }
} );
"""

with open(os.path.join(plugin_dir, "aurika-theme-installer", "aurika-theme-installer.php"), "w", encoding="utf-8") as f:
    f.write(plugin_php)

plugin_zip = os.path.join(output_dir, "aurika-theme-installer-plugin.zip")
if os.path.exists(plugin_zip):
    os.remove(plugin_zip)

count = 0
with zipfile.ZipFile(plugin_zip, 'w', zipfile.ZIP_DEFLATED) as zf:
    for root, dirs, files in os.walk(plugin_dir):
        for file in files:
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, plugin_dir).replace('\\', '/')
            zf.write(full_path, rel_path)
            count += 1

shutil.rmtree(plugin_dir)
print(f"3. aurika-theme-installer-plugin.zip created with {count} files ({os.path.getsize(plugin_zip)/1024/1024:.2f} MB)")

print("\n--- All v5.7 Packages Built Successfully ---")
