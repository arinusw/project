#!/usr/bin/env python3
import os
import sys
import re

def main():
    root_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    ar_creative_dir = os.path.join(root_dir, 'ar-creative')
    index_file = os.path.join(ar_creative_dir, 'index.html')

    print("=== Checking Ar Creative Assets & Files ===")

    if not os.path.exists(index_file):
        print(f"ERROR: {index_file} not found!")
        sys.exit(1)

    with open(index_file, 'r', encoding='utf-8') as f:
        html_content = f.read()

    # Check referenced local assets in index.html
    css_files = re.findall(r'href=["\'](css/[^"\'?]+)', html_content)
    js_files = re.findall(r'src=["\'](js/[^"\'?]+)', html_content)
    img_files = re.findall(r'src=["\'](assets/[^"\'?]+)', html_content)

    all_assets = css_files + js_files + img_files
    missing = []

    for asset in all_assets:
        asset_path = os.path.join(ar_creative_dir, asset)
        if not os.path.exists(asset_path):
            missing.append(asset)
            print(f"❌ Missing asset: {asset}")
        else:
            print(f"✅ Found asset: {asset}")

    if missing:
        print(f"FAILED: {len(missing)} asset(s) missing.")
        sys.exit(1)
    else:
        print("ALL STATIC ASSETS VERIFIED SUCCESSFULLY!")

if __name__ == '__main__':
    main()
