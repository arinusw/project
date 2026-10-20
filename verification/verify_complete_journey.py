import time
import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down step-by-step to trigger ScrollReveal
    for i in range(1, 10):
        await page.mouse.wheel(0, 400)
        await asyncio.sleep(0.2)
    # Scroll back to top instantly
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await asyncio.sleep(0.5)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # --- DESKTOP VERIFICATION ---
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context.new_page()

        print("Navigating to http://127.0.0.1:8000/...")
        await page.goto('http://127.0.0.1:8000/')
        await page.wait_for_load_state('networkidle')
        await asyncio.sleep(1)

        # Desktop Light Theme Top
        await page.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)
        print("Captured desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page.locator('#theme-button')
        await theme_btn.click()
        await asyncio.sleep(0.5)
        await page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)
        print("Captured desktop_dark.png")

        # Toggle back to Light Theme
        await theme_btn.click()
        await asyncio.sleep(0.5)

        # Scroll trigger
        await trigger_scroll_animations(page)

        # Test Contact Form Submission via AJAX
        print("Testing Contact Form submission...")
        await page.locator('#contact').scroll_into_view_if_needed()
        await asyncio.sleep(0.5)

        await page.fill('#name', 'Budi Pratama')
        await page.fill('#email', 'budi@example.com')
        await page.fill('#subject', 'Permintaan Penawaran Website')
        await page.fill('#message', 'Halo Ar Creative, saya ingin berkonsultasi mengenai pembuatan website portal edukasi.')

        # Submit form
        await page.click('button[type="submit"]')

        # Wait for feedback message
        contact_msg = page.locator('#contact-message')
        await contact_msg.wait_for(state="visible", timeout=5000)
        msg_text = await contact_msg.inner_text()
        print(f"Contact Form Response: {msg_text}")

        await page.screenshot(path='verification/screenshots/contact_submitted.png', full_page=False)
        print("Captured contact_submitted.png")

        await context.close()

        # --- MOBILE VERIFICATION ---
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15'
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto('http://127.0.0.1:8000/')
        await mobile_page.wait_for_load_state('networkidle')
        await asyncio.sleep(1)

        # Mobile Light Theme Top
        await mobile_page.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)
        print("Captured mobile_light.png")

        # Open Mobile Nav Drawer
        nav_toggle = mobile_page.locator('#nav-toggle')
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.5)

        await mobile_page.screenshot(path='verification/screenshots/mobile_menu.png', full_page=False)
        print("Captured mobile_menu.png")

        # Click menu item in #nav-menu
        nav_services = mobile_page.locator('#nav-menu a[href="#services"]')
        await nav_services.click()
        await asyncio.sleep(0.5)

        # Scroll slightly to trigger ScrollReveal reveal on mobile
        await mobile_page.mouse.wheel(0, 150)
        await asyncio.sleep(0.5)

        # Capture Services section on mobile
        await mobile_page.screenshot(path='verification/screenshots/mobile_services.png', full_page=False)
        print("Captured mobile_services.png")

        # Capture Final CUJ screenshot
        await mobile_page.screenshot(path='verification/screenshots/final_cuj.png', full_page=False)
        print("Captured final_cuj.png")

        await mobile_context.close()
        await browser.close()
        print("Verification complete!")

if __name__ == '__main__':
    asyncio.run(main())
