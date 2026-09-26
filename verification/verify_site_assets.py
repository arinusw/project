import os

def test_site_assets():
    base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    ar_creative_dir = os.path.join(base_dir, 'ar-creative')

    required_files = [
        os.path.join(ar_creative_dir, 'index.html'),
        os.path.join(ar_creative_dir, 'css', 'styles.css'),
        os.path.join(ar_creative_dir, 'js', 'main.js'),
        os.path.join(ar_creative_dir, 'contact.php')
    ]

    for filepath in required_files:
        assert os.path.exists(filepath), f"Missing required file: {filepath}"

    with open(os.path.join(ar_creative_dir, 'index.html'), 'r', encoding='utf-8') as f:
        html_content = f.read()

    assert 'Ar Creative' in html_content
    assert 'Jasa & Produk Digital' in html_content
    assert 'Edukasi Teknologi' in html_content
    assert 'https://www.facebook.com/arkreative' in html_content
    assert 'https://www.instagram.com/arcreative_26' in html_content
    assert 'https://www.youtube.com/@arcreative-24' in html_content
    assert '+62 812 4861 6454' in html_content

    print("Asset verification passed successfully!")

if __name__ == '__main__':
    test_site_assets()
