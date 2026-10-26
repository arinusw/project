import asyncio
from playwright.async_api import async_playwright

async def run_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_page = await browser.new_page(viewport={"width": 1280, "height": 800})
        await desktop_page.goto("http://127.0.0.1:8000/")
        await desktop_page.wait_for_selector("#home")

        # Scroll down to reveal all sections
        await desktop_page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
        await desktop_page.wait_for_timeout(1000)
        await desktop_page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await desktop_page.wait_for_timeout(500)

        # Desktop Light Screenshot
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png")

        # Toggle Dark Theme
        theme_btn = desktop_page.locator("#theme-button")
        await theme_btn.click()
        await desktop_page.wait_for_timeout(500)
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png")

        # Submit Contact Form (Simulation Mode)
        await desktop_page.locator("#name").fill("Pengunjung Test")
        await desktop_page.locator("#email").fill("test@example.com")
        await desktop_page.locator("#subject").fill("Pertanyaan Layanan Web")
        await desktop_page.locator("#message").fill("Halo Ar Creative, saya tertarik dengan layanan pembuatan website.")

        # Inject simulation mode input
        await desktop_page.evaluate("""
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        """)

        await desktop_page.locator(".contact__submit-btn").click()
        await desktop_page.wait_for_selector(".contact__message.color-blue")
        await desktop_page.screenshot(path="verification/screenshots/desktop_form_submitted.png")

        # 2. Mobile Verification
        mobile_page = await browser.new_page(viewport={"width": 375, "height": 667})
        await mobile_page.goto("http://127.0.0.1:8000/")
        await mobile_page.wait_for_selector("#home")
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png")

        # Open mobile menu
        await mobile_page.locator("#nav-toggle").click(force=True)
        await mobile_page.wait_for_timeout(500)
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        await browser.close()
        print("Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_verification())
