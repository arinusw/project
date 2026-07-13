from playwright.sync_api import sync_playwright
import os

def run_cuj(page):
    page.goto("http://127.0.0.1:8000")
    page.wait_for_timeout(1000)

    # 1. Show Home
    page.wait_for_timeout(500)

    # 2. Navigate to About (scoping to nav)
    page.locator("#nav-menu").get_by_role("link", name="Tentang").click()
    page.wait_for_timeout(1000)

    # 3. Navigate to Services
    page.locator("#nav-menu").get_by_role("link", name="Layanan").click()
    page.wait_for_timeout(1000)

    # 4. Fill and Submit Contact Form
    page.locator("#nav-menu").get_by_role("link", name="Kontak").click()
    page.wait_for_timeout(500)

    page.get_by_label("Nama").fill("Jules Engineer")
    page.wait_for_timeout(500)
    page.get_by_label("Email").fill("jules@example.com")
    page.wait_for_timeout(500)
    page.get_by_label("Proyek / Pesan").fill("Halo, ini adalah pesan uji coba untuk portofolio Ar Creative.")
    page.wait_for_timeout(500)

    page.get_by_role("button", name="Kirim Pesan").click()
    page.wait_for_timeout(2000)

    # Take screenshot of the success state
    page.screenshot(path="/home/jules/verification/screenshots/verification_success.png")
    page.wait_for_timeout(1000)

if __name__ == "__main__":
    os.makedirs("/home/jules/verification/videos", exist_ok=True)
    os.makedirs("/home/jules/verification/screenshots", exist_ok=True)
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            record_video_dir="/home/jules/verification/videos"
        )
        page = context.new_page()
        try:
            run_cuj(page)
        finally:
            context.close()
            browser.close()
