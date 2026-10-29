import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down step-by-step to trigger ScrollReveal animations
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(200)
    # Scroll back to top
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(300)

async def run_tests():
    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # Desktop Testing
        desktop_page = await browser.new_page(viewport={"width": 1280, "height": 800})
        await desktop_page.goto("http://127.0.0.1:8000/index.html")
        await desktop_page.wait_for_load_state("networkidle")
        await trigger_scroll_animations(desktop_page)

        # Desktop Light Mode Screenshot
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png")

        # Desktop Dark Mode Toggle
        await desktop_page.click("#theme-button")
        await desktop_page.wait_for_timeout(500)
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png")

        # Contact Form Submission (Simulation Mode)
        await desktop_page.fill("#name", "Budi Pratama")
        await desktop_page.fill("#email", "budi@example.com")
        await desktop_page.fill("#subject", "Konsultasi Project Website")
        await desktop_page.fill("#message", "Halo Ar Creative, saya tertarik untuk menggunakan jasa pembuatan website digital.")

        # Append simulation mode field dynamically or test direct form response
        await desktop_page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        }""")

        await desktop_page.click("#contact-form button[type='submit']")
        await desktop_page.wait_for_selector(".contact__message.success", timeout=5000)
        await desktop_page.screenshot(path="verification/screenshots/desktop_form_submitted.png")

        # Mobile Testing
        mobile_page = await browser.new_page(viewport={"width": 375, "height": 667})
        await mobile_page.goto("http://127.0.0.1:8000/index.html")
        await mobile_page.wait_for_load_state("networkidle")

        # Mobile Light Screenshot
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png")

        # Mobile Navigation Menu Toggle
        await mobile_page.click("#nav-toggle", force=True)
        await mobile_page.wait_for_selector("#nav-menu.show-menu")
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        await mobile_page.click("#nav-close")
        await mobile_page.wait_for_timeout(300)

        # Mobile Dark Screenshot
        await mobile_page.click("#theme-button")
        await mobile_page.wait_for_timeout(300)
        await mobile_page.screenshot(path="verification/screenshots/mobile_dark.png")

        await browser.close()
        print("UI Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_tests())
