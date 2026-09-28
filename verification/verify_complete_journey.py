import os
from playwright.sync_api import sync_playwright

def main():
    os.makedirs('verification/videos', exist_ok=True)
    os.makedirs('verification/screenshots', exist_ok=True)

    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = context.new_page()

        print("Navigating to http://127.0.0.1:8000/...")
        page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        page.wait_for_timeout(1000)

        # Smooth Scroll Journey through sections
        sections = ["about", "services", "education", "social", "contact"]
        for section in sections:
            page.evaluate(f"document.getElementById('{section}').scrollIntoView({{behavior: 'smooth'}})")
            page.wait_for_timeout(1200)

        # Toggle Light / Dark Mode twice
        page.evaluate("window.scrollTo({top: 0, behavior: 'smooth'})")
        page.wait_for_timeout(1000)
        page.click("#theme-button")
        page.wait_for_timeout(1000)
        page.click("#theme-button")
        page.wait_for_timeout(1000)

        # Scroll down to Contact & Submit Form
        page.evaluate("document.getElementById('contact').scrollIntoView({behavior: 'smooth'})")
        page.wait_for_timeout(1000)

        page.evaluate("""
            const form = document.getElementById('contact-form');
            if (form && !document.getElementById('sim-input')) {
                const simInput = document.createElement('input');
                simInput.id = 'sim-input';
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            }
        """)

        page.fill("#name", "Siti Rahma")
        page.fill("#email", "siti@arcreative.id")
        page.fill("#subject", "Edukasi & Workshop Digital")
        page.fill("#message", "Halo Ar Creative, kami ingin mengundang Ar Creative mengisi workshop literasi teknologi.")
        page.wait_for_timeout(500)

        page.click("#contact-submit-btn")
        page.locator("#contact-message").wait_for(state="visible", timeout=5000)
        page.wait_for_timeout(2000)

        page.screenshot(path="verification/screenshots/full_cuj_final.png")

        context.close()
        browser.close()

    print("Complete user journey video recorded in verification/videos/")

if __name__ == "__main__":
    main()
