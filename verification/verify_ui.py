import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    """Simulate smooth wheel scrolling to trigger ScrollReveal animations."""
    sections = ['#home', '#about', '#services', '#education', '#social', '#contact']
    for sec in sections:
        await page.locator(sec).scroll_into_view_if_needed()
        await page.mouse.wheel(0, 300)
        await page.wait_for_timeout(300)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # ------------------ Desktop Verification (1280x800) ------------------
        desktop_context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await desktop_context.new_page()
        await page.goto('http://127.0.0.1:8000')

        # Trigger animations and scroll back to top for stable initial view
        await trigger_scroll_animations(page)
        await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page.wait_for_timeout(500)

        # Capture Desktop Light Theme screenshot
        await page.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Toggle Dark Theme
        theme_btn = page.locator('#theme-button')
        await theme_btn.click()
        await page.wait_for_timeout(500)
        await page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        # Navigate to Contact section & submit form in simulation mode
        await page.locator('#contact').scroll_into_view_if_needed()
        await page.locator('#name').fill('Budi Santoso')
        await page.locator('#email').fill('budi@example.com')
        await page.locator('#subject').fill('Konsultasi Solusi Digital')
        await page.locator('#message').fill('Halo Ar Creative, saya berminat untuk mendiskusikan pembuatan website dan pelatihan digital.')

        # Inject simulation mode flag into form dynamically
        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        await page.locator('#contact-form button[type="submit"]').click()
        await page.locator('#contact-message').wait_for(state="visible")
        await page.wait_for_timeout(500)
        await page.screenshot(path='verification/screenshots/desktop_contact_submitted.png', full_page=False)

        await desktop_context.close()

        # ------------------ Mobile Verification (375x667) ------------------
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1'
        )
        page_mobile = await mobile_context.new_page()
        await page_mobile.goto('http://127.0.0.1:8000')

        await trigger_scroll_animations(page_mobile)
        await page_mobile.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await page_mobile.wait_for_timeout(500)

        # Capture Mobile Light Theme home
        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)

        # Toggle Mobile Navigation Menu
        nav_toggle = page_mobile.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await page_mobile.locator('#nav-menu').wait_for(state="visible")
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png', full_page=False)

        await mobile_context.close()
        await browser.close()
        print("UI Verification Completed Successfully!")

if __name__ == '__main__':
    asyncio.run(main())
