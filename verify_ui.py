import asyncio
import os
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down incrementally to trigger ScrollReveal entrance animations
    for i in range(10):
        await page.mouse.wheel(0, 600)
        await asyncio.sleep(0.2)
    # Scroll back to top
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.5)

async def run_verification():
    os.makedirs('verification/screenshots', exist_ok=True)
    os.makedirs('verification/videos', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # ----------------------------------------------------
        # 1. Desktop Light Theme & Interaction
        # ----------------------------------------------------
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir="verification/videos/"
        )
        page_desktop = await context_desktop.new_page()
        await page_desktop.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        await trigger_scroll_animations(page_desktop)
        await page_desktop.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)

        # Contact Form Test (Desktop Light)
        await page_desktop.locator('#contact').scroll_into_view_if_needed()
        await asyncio.sleep(0.3)
        await page_desktop.fill("#user_name", "Ahmad Tester")
        await page_desktop.fill("#user_email", "ahmad@example.com")
        await page_desktop.fill("#user_project", "Halo Ar Creative, saya tertarik dengan jasa pembuatan website portfolio.")

        # Set simulation mode hidden field or evaluate POST
        await page_desktop.evaluate("""
            const form = document.getElementById('contact-form');
            if(form) {
                let sim = form.querySelector('input[name="simulation_mode"]');
                if(!sim) {
                    sim = document.createElement('input');
                    sim.type = 'hidden';
                    sim.name = 'simulation_mode';
                    sim.value = '1';
                    form.appendChild(sim);
                }
            }
        """)

        await page_desktop.click('#contact-form button[type="submit"]')
        await page_desktop.locator('#contact-message').wait_for(state="visible")
        await asyncio.sleep(0.5)
        await page_desktop.screenshot(path="verification/screenshots/desktop_form_submitted.png")

        # Toggle to Dark Mode
        await page_desktop.evaluate("window.scrollTo(0, 0)")
        await asyncio.sleep(0.3)
        await page_desktop.click('#theme-button')
        await asyncio.sleep(0.5)
        await page_desktop.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)

        await context_desktop.close()

        # ----------------------------------------------------
        # 2. Mobile Light Theme & Drawer Test
        # ----------------------------------------------------
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True,
            user_agent="Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15",
            record_video_dir="verification/videos/"
        )
        page_mobile = await context_mobile.new_page()
        await page_mobile.goto("http://127.0.0.1:8000/", wait_until="networkidle")

        await trigger_scroll_animations(page_mobile)
        await page_mobile.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

        # Open Mobile Navigation Menu
        await page_mobile.click('#nav-toggle', force=True)
        await asyncio.sleep(0.5)
        await page_mobile.screenshot(path="verification/screenshots/mobile_nav_open.png")

        # Toggle Dark Mode on Mobile
        await page_mobile.click('#theme-button', force=True)
        await asyncio.sleep(0.5)
        await page_mobile.screenshot(path="verification/screenshots/mobile_dark.png", full_page=True)

        await context_mobile.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run_verification())
