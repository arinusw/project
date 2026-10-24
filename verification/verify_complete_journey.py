import asyncio
from playwright.async_api import async_playwright
import os

async def trigger_scroll_animations(page):
    # Scroll down step-by-step to trigger ScrollReveal animations
    height = await page.evaluate("document.body.scrollHeight")
    step = 300
    for y in range(0, height, step):
        await page.mouse.wheel(0, step)
        await asyncio.sleep(0.1)
    await asyncio.sleep(0.5)

async def run_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification
        context_desktop = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context_desktop.new_page()
        await page.goto("http://1280.0.0.1:8000" if False else "http://127.0.0.1:8000")
        await page.wait_for_load_state("networkidle")

        # Scroll to reveal all entrance animations
        await trigger_scroll_animations(page)

        # Reset scroll to top instantly for top screenshot
        await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
        await asyncio.sleep(0.3)
        await page.screenshot(path="verification/screenshots/desktop_light.png")

        # Toggle Dark Theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(0.3)
        await page.screenshot(path="verification/screenshots/desktop_dark.png")

        # Fill & Submit Contact Form (Simulation mode)
        await page.evaluate("document.getElementById('contact').scrollIntoView()")
        await asyncio.sleep(0.5)

        await page.fill("#name", "Budi Santoso")
        await page.fill("#email", "budi@example.com")
        await page.fill("#message", "Halo Ar Creative, saya tertarik dengan jasa pembuatan website profesional.")

        # Add hidden simulation_mode input
        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const sim = document.createElement('input');
            sim.type = 'hidden';
            sim.name = 'simulation_mode';
            sim.value = '1';
            form.appendChild(sim);
        }""")

        await page.click("#contact-form button[type='submit']")
        await page.locator("#contact-message").wait_for(state="visible")
        await asyncio.sleep(0.5)
        await page.screenshot(path="verification/screenshots/desktop_contact_submitted.png")

        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True
        )
        page_m = await context_mobile.new_page()
        await page_m.goto("http://127.0.0.1:8000")
        await page_m.wait_for_load_state("networkidle")

        await page_m.screenshot(path="verification/screenshots/mobile_light.png")

        # Open Mobile Menu
        nav_toggle = page_m.locator("#nav-toggle")
        await nav_toggle.click(force=True)
        await asyncio.sleep(0.5)
        await page_m.screenshot(path="verification/screenshots/mobile_menu_open.png")

        # Close Mobile Menu
        nav_close = page_m.locator("#nav-close")
        await nav_close.click(force=True)
        await asyncio.sleep(0.3)

        await context_mobile.close()
        await browser.close()
        print("Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(run_verification())
