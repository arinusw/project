import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Perform smooth incremental scrolling to trigger ScrollReveal entrance animations across all sections
    for i in range(12):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(200)

async def verify_site():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_page = await browser.new_page(viewport={"width": 1280, "height": 800})
        await desktop_page.goto("http://127.0.0.1:8000")
        await desktop_page.wait_for_selector("#home")

        # Scroll to top instantly before capturing top desktop view
        await desktop_page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await desktop_page.screenshot(path="verification/screenshots/desktop_light.png")

        # Dark theme check
        await desktop_page.click("#theme-button")
        await desktop_page.wait_for_timeout(300)
        await desktop_page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await desktop_page.screenshot(path="verification/screenshots/desktop_dark.png")
        await desktop_page.click("#theme-button") # Switch back to light theme

        # Scroll trigger animations
        await trigger_scroll_animations(desktop_page)
        await desktop_page.screenshot(path="verification/screenshots/desktop_scrolled.png")

        # Fill and submit contact form in simulation mode
        await desktop_page.evaluate("""
            const form = document.querySelector('#contact-form');
            if (form) {
                let simInput = document.createElement('input');
                simInput.type = 'hidden';
                simInput.name = 'simulation_mode';
                simInput.value = '1';
                form.appendChild(simInput);
            }
        """)

        await desktop_page.fill("#name", "Budi Santoso")
        await desktop_page.fill("#email", "budi@example.com")
        await desktop_page.fill("#subject", "Permintaan Solusi Digital & Landing Page")
        await desktop_page.fill("#message", "Halo Ar Creative, kami ingin berdiskusi mengenai pengembangan produk digital dan pelatihan literasi teknologi untuk tim kami.")

        await desktop_page.click("#contact-button")
        await desktop_page.wait_for_selector(".contact__message.success", state="visible")
        await desktop_page.screenshot(path="verification/screenshots/desktop_contact_success.png")

        # 2. Mobile Verification
        mobile_page = await browser.new_page(viewport={"width": 375, "height": 667})
        await mobile_page.goto("http://127.0.0.1:8000")
        await mobile_page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await mobile_page.screenshot(path="verification/screenshots/mobile_light.png")

        # Mobile Menu interaction
        await mobile_page.click("#nav-toggle", force=True)
        await mobile_page.wait_for_selector("#nav-menu.show-menu", state="visible")
        await mobile_page.screenshot(path="verification/screenshots/mobile_menu_open.png")

        await mobile_page.click("#nav-close")
        await mobile_page.wait_for_timeout(300)

        # Scroll through mobile view
        await trigger_scroll_animations(mobile_page)
        await mobile_page.screenshot(path="verification/screenshots/mobile_scrolled.png")

        await browser.close()
        print("UI Verification successfully executed and screenshots captured.")

if __name__ == "__main__":
    asyncio.run(verify_site())
