import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll incrementally to trigger ScrollReveal entrance animations
    for _ in range(5):
        await page.mouse.wheel(0, 800)
        await asyncio.sleep(0.3)
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.5)

async def main():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # --- DESKTOP VERIFICATION ---
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto('http://127.0.0.1:8000') # local server URL
        await page_desktop.wait_for_load_state('networkidle')

        # Trigger animations and capture light theme desktop
        await trigger_scroll_animations(page_desktop)
        await page_desktop.screenshot(path='verification/screenshots/desktop_light.png', full_page=True)
        print("Desktop light screenshot saved.")

        # Toggle dark theme
        theme_btn = page_desktop.locator('#theme-button')
        await theme_btn.click()
        await asyncio.sleep(0.5)
        await page_desktop.screenshot(path='verification/screenshots/desktop_dark.png', full_page=True)
        print("Desktop dark screenshot saved.")

        # Test contact form with simulation_mode hidden field injected or AJAX
        await page_desktop.evaluate("""() => {
            let form = document.getElementById('contact-form');
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        }""")

        await page_desktop.fill('#name', 'Budi Santoso')
        await page_desktop.fill('#email', 'budi@example.com')
        await page_desktop.fill('#subject', 'Permintaan Penawaran Website')
        await page_desktop.fill('#message', 'Halo Ar Creative, saya tertarik menggunakan jasa pembuatan website.')
        await page_desktop.click('.contact__submit-btn')

        # Wait for success message
        await page_desktop.locator('#contact-message').wait_for(state='visible')
        await asyncio.sleep(1)
        await page_desktop.screenshot(path='verification/screenshots/desktop_form_submitted.png')
        print("Desktop form submission screenshot saved.")

        await context_desktop.close()

        # --- MOBILE VERIFICATION ---
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            has_touch=True,
            record_video_dir='verification/videos/'
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto('http://127.0.0.1:8000')
        await page_mobile.wait_for_load_state('networkidle')

        await trigger_scroll_animations(page_mobile)
        await page_mobile.screenshot(path='verification/screenshots/mobile_light.png', full_page=True)
        print("Mobile light screenshot saved.")

        # Open mobile drawer
        toggle = page_mobile.locator('#nav-toggle')
        await toggle.click(force=True)
        await asyncio.sleep(0.5)
        await page_mobile.screenshot(path='verification/screenshots/mobile_menu_open.png')
        print("Mobile menu open screenshot saved.")

        # Close mobile drawer
        close_btn = page_mobile.locator('#nav-close')
        await close_btn.click(force=True)
        await asyncio.sleep(0.5)

        # Toggle dark mode on mobile
        theme_btn_mobile = page_mobile.locator('#theme-button')
        await theme_btn_mobile.click()
        await asyncio.sleep(0.5)
        await page_mobile.screenshot(path='verification/screenshots/mobile_dark.png', full_page=True)
        print("Mobile dark screenshot saved.")

        await context_mobile.close()

        await browser.close()

if __name__ == '__main__':
    asyncio.run(main())
