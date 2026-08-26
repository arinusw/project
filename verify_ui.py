import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll slowly down to trigger ScrollReveal animations
    for i in range(10):
        await page.mouse.wheel(0, 500)
        await page.wait_for_timeout(200)
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(300)

async def verify():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto('http://127.0.0.1:8000')
        await page_desktop.wait_for_timeout(1000)

        # Trigger scroll reveal
        await trigger_scroll_animations(page_desktop)

        # Screenshot Desktop Light Mode
        await page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)
        print("Captured desktop_light.png")

        # Toggle Dark Mode
        theme_btn = page_desktop.locator('#theme-button')
        await theme_btn.click()
        await page_desktop.wait_for_timeout(500)
        await page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)
        print("Captured desktop_dark.png")

        # Fill and Submit Contact Form in Simulation Mode
        await page_desktop.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await page_desktop.fill('#user_name', 'Budi Santoso')
        await page_desktop.fill('#user_email', 'budi@example.com')
        await page_desktop.fill('#user_project', 'Halo Ar Creative, saya tertarik untuk pembuatan website portofolio dan branding.')

        await page_desktop.click('button[type="submit"]')
        await page_desktop.wait_for_timeout(1000)
        await page_desktop.screenshot(path='verification/screenshots/contact_form_submitted.png')
        print("Captured contact_form_submitted.png")

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            record_video_dir="verification/videos/"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000')
        await page_mobile.wait_for_timeout(1000)

        await trigger_scroll_animations(page_mobile)
        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=True)
        print("Captured mobile_light.png")

        # Mobile Menu Toggle
        nav_toggle = page_mobile.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await page_mobile.wait_for_timeout(500)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Captured mobile_menu_open.png")

        await context_mobile.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(verify())
