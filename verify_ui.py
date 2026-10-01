import asyncio
import os
import sys
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll in steps to trigger ScrollReveal animations across sections
    for _ in range(6):
        await page.mouse.wheel(0, 700)
        await page.wait_for_timeout(300)
    # Scroll back to top
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(500)

async def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        desktop_context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        desktop_page = await desktop_context.new_page()
        await desktop_page.goto('http://127.0.0.1:8000', wait_until='networkidle')

        await trigger_scroll_animations(desktop_page)

        # Capture Desktop Light Viewport
        await desktop_page.screenshot(path='verification/screenshots/desktop_light.png')
        print("Captured desktop_light.png")

        # Toggle Dark Mode
        theme_btn = desktop_page.locator('#theme-button')
        await theme_btn.click()
        await desktop_page.wait_for_timeout(300)
        await desktop_page.screenshot(path='verification/screenshots/desktop_dark.png')
        print("Captured desktop_dark.png")

        await desktop_context.close()

        # 2. Mobile Verification
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            has_touch=True
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto('http://127.0.0.1:8000', wait_until='networkidle')

        await trigger_scroll_animations(mobile_page)
        await mobile_page.screenshot(path='verification/screenshots/mobile_light.png')
        print("Captured mobile_light.png")

        # Mobile Menu Test
        nav_toggle = mobile_page.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await mobile_page.wait_for_timeout(300)
        await mobile_page.screenshot(path='verification/screenshots/mobile_menu.png')
        print("Captured mobile_menu.png")

        # Click link to close menu
        about_link = mobile_page.locator('#nav-menu a[href="#about"]')
        await about_link.click(force=True)
        await mobile_page.wait_for_timeout(500)

        # Contact Form Test
        await mobile_page.goto('http://127.0.0.1:8000#contact', wait_until='networkidle')
        await mobile_page.wait_for_timeout(500)

        await mobile_page.fill('#contact-name', 'Budi Santoso')
        await mobile_page.fill('#contact-email', 'budi@example.com')
        await mobile_page.fill('#contact-subject', 'Pertanyaan Layanan Web')
        await mobile_page.fill('#contact-message', 'Halo Ar Creative, saya tertarik dengan layanan pengembangan website.')

        # Append simulation mode field dynamically or test AJAX
        await mobile_page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        }""")

        submit_btn = mobile_page.locator('#contact-form button[type="submit"]')
        await submit_btn.click()

        # Wait for success message
        response_msg = mobile_page.locator('#contact-response')
        await response_msg.wait_for(state='visible')
        await mobile_page.wait_for_timeout(500)

        await mobile_page.screenshot(path='verification/screenshots/contact_form_submitted.png')
        print("Captured contact_form_submitted.png")

        await mobile_context.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run_verification())
