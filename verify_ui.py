import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Incrementally scroll down to trigger ScrollReveal entrance animations for all sections
    total_height = await page.evaluate("document.body.scrollHeight")
    for y in range(0, total_height, 400):
        await page.mouse.wheel(0, 400)
        await page.wait_for_timeout(100)
    await page.wait_for_timeout(500)
    # Scroll back to top
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(500)

async def run_verification():
    screenshots_dir = "verification/screenshots"
    videos_dir = "verification/videos"
    os.makedirs(screenshots_dir, exist_ok=True)
    os.makedirs(videos_dir, exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # --- DESKTOP VERIFICATION ---
        context_desktop = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir=videos_dir
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/index.html")
        await page_desktop.wait_for_load_state("networkidle")

        await trigger_scroll_animations(page_desktop)

        # 1. Desktop Light Theme Screenshot (Home Hero View)
        await page_desktop.evaluate("window.scrollTo(0, 0)")
        await page_desktop.wait_for_timeout(300)
        await page_desktop.screenshot(path=os.path.join(screenshots_dir, "desktop_light.png"))

        # 2. Toggle Dark Theme
        theme_btn = page_desktop.locator("#theme-button")
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path=os.path.join(screenshots_dir, "desktop_dark.png"))

        # 3. Test Contact Form Submission in Simulation Mode
        await page_desktop.locator("#contact").scroll_into_view_if_needed()
        await page_desktop.wait_for_timeout(300)
        await page_desktop.fill("#name", "Budi Pratama")
        await page_desktop.fill("#email", "budi@example.com")
        await page_desktop.fill("#message", "Halo Ar Creative, saya tertarik dengan jasa pembuatan website.")

        # Inject simulation mode flag into contact form before submission
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await page_desktop.click("button.contact__submit-btn")
        await page_desktop.wait_for_selector("#contact-message:has-text('Pesan Anda berhasil terkirim')", timeout=5000)
        await page_desktop.screenshot(path=os.path.join(screenshots_dir, "contact_form_success.png"))

        await context_desktop.close()

        # --- MOBILE VERIFICATION ---
        context_mobile = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/index.html")
        await page_mobile.wait_for_load_state("networkidle")

        await trigger_scroll_animations(page_mobile)

        # 1. Mobile Home View
        await page_mobile.evaluate("window.scrollTo(0, 0)")
        await page_mobile.wait_for_timeout(300)
        await page_mobile.screenshot(path=os.path.join(screenshots_dir, "mobile_home.png"))

        # 2. Mobile Menu Toggle
        nav_toggle = page_mobile.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(400)
        await page_mobile.screenshot(path=os.path.join(screenshots_dir, "mobile_menu_open.png"))

        await context_mobile.close()
        await browser.close()

        print("Playwright UI verification completed successfully!")

if __name__ == '__main__':
    asyncio.run(run_verification())
