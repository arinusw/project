import os
import sys

def verify_site_assets():
    base_dir = os.path.join(os.getcwd(), 'ar-creative')
    required_files = [
        'index.html',
        'css/styles.css',
        'js/main.js',
        'contact.php',
        'assets/hero.svg',
        'assets/about.svg',
        'assets/education.svg',
        'assets/service-creative.svg',
        'assets/service-product.svg',
        'assets/service-multimedia.svg',
        'assets/service-education.svg'
    ]

    missing = []
    for rel_path in required_files:
        full_path = os.path.join(base_dir, rel_path)
        if not os.path.exists(full_path):
            missing.append(rel_path)

    if missing:
        print(f"FAILED: Missing files: {missing}")
        sys.exit(1)

    print("SUCCESS: All required assets verified!")

if __name__ == '__main__':
    verify_site_assets()
