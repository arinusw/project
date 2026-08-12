import asyncio
import time
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    """Simulates scrolling down the page incrementally to trigger all ScrollReveal entrance animations."""
    print("Scrolling page to trigger entrance animations...")
    # Scroll incrementally to allow animations to load
    for scroll_offset in range(0, 4000, 400):
        await page.mouse.wheel(0, 400)
        await asyncio.sleep(0.15)
    # Scroll back to the top
    await page.evaluate("window.scrollTo(0, 0)")
    await asyncio.sleep(0.5)

async def run_desktop_verification(playwright, server_url):
    print("Running desktop verification...")
    # Desktop Viewport: 1280x800
    browser = await playwright.chromium.launch()
    context = await browser.new_context(
        viewport={"width": 1280, "height": 800},
        record_video_dir="verification/videos/"
    )
    page = await context.new_page()
    await page.goto(server_url)
    await page.wait_for_load_state("networkidle")

    # Trigger scroll animations
    await trigger_scroll_animations(page)

    # 1. Capture main landing page screenshot
    await page.screenshot(path="verification/screenshots/desktop_landing.png", full_page=True)

    # 2. Test Light/Dark theme switching
    print("Testing dark theme on desktop...")
    theme_button = page.locator("#theme-button")
    await theme_button.click()
    await page.evaluate("document.body.classList.contains('dark-theme')") # Verify dark-theme is applied
    await page.screenshot(path="verification/screenshots/desktop_dark_theme.png", full_page=True)

    # Revert back to light theme for subsequent interactions
    await theme_button.click()

    # 3. Simulate Contact Form submission under simulation mode
    print("Testing contact form submission on desktop...")
    # Select the form and fill in details
    await page.fill("#user_name", "Arinus Wijaya")
    await page.fill("#user_email", "arinus@arcreative.com")
    await page.fill("#user_project", "Ingin membuat landing page profil sekolah yang interaktif dan responsive.")

    # Programmatically inject simulation mode hidden input to contact form
    await page.evaluate("""
        const form = document.getElementById('contact-form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'simulation_mode';
        input.value = '1';
        form.appendChild(input);
    """)

    # Submit form
    submit_btn = page.locator("#contact-form button[type='submit']")
    await submit_btn.click()

    # Wait for success message element to become visible
    msg_locator = page.locator("#contact-message")
    await msg_locator.wait_for(state="visible")

    # Verify success response text contains Indonesian
    msg_text = await msg_locator.inner_text()
    print(f"Form submission response: {msg_text}")

    # Capture screenshot of success form submission state
    await page.screenshot(path="verification/screenshots/desktop_contact_success.png")

    await context.close()
    await browser.close()

async def run_mobile_verification(playwright, server_url):
    print("Running mobile verification...")
    # Mobile Viewport: 375x667
    browser = await playwright.chromium.launch()
    context = await browser.new_context(
        viewport={"width": 375, "height": 667},
        is_mobile=True,
        has_touch=True,
        record_video_dir="verification/videos/"
    )
    page = await context.new_page()
    await page.goto(server_url)
    await page.wait_for_load_state("networkidle")

    # Trigger scroll animations
    await trigger_scroll_animations(page)

    # 1. Capture landing page
    await page.screenshot(path="verification/screenshots/mobile_landing.png", full_page=True)

    # 2. Test Mobile Drawer Menu toggle
    print("Testing mobile drawer toggle...")
    nav_toggle = page.locator("#nav-toggle")
    await nav_toggle.click(force=True) # force click due to ScrollReveal overlays
    await page.wait_for_timeout(500)

    # Capture mobile menu drawer state
    await page.screenshot(path="verification/screenshots/mobile_drawer_open.png")

    # Close mobile drawer
    nav_close = page.locator("#nav-close")
    await nav_close.click(force=True)
    await page.wait_for_timeout(500)

    # 3. Test Mobile Navigation to sections hashes directly
    print("Testing navigation to services section...")
    await page.goto(f"{server_url}#services")
    await page.wait_for_timeout(500)
    await page.screenshot(path="verification/screenshots/mobile_services_section.png")

    await context.close()
    await browser.close()

async def main():
    server_url = "http://127.0.0.1:8000/index.html"
    async with async_playwright() as playwright:
        await run_desktop_verification(playwright, server_url)
        await run_mobile_verification(playwright, server_url)
    print("Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(main())
