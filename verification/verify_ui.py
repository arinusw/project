import asyncio
import os
import sys
from playwright.async_api import async_playwright

async def run_desktop_test(page):
    print("--- [Desktop UI Verification] ---")
    await page.goto("http://127.0.0.1:8000")
    await page.wait_for_timeout(1000)

    # 1. Check navigation elements
    nav_logo = page.locator(".nav__logo")
    await nav_logo.wait_for(state="visible")
    assert await nav_logo.is_visible(), "Logo must be visible on desktop."
    print("Desktop Logo visibility verified.")

    # 2. Toggle Theme
    theme_button = page.locator("#theme-button")
    await theme_button.click()
    await page.wait_for_timeout(500)
    body_class_dark = await page.locator("body").evaluate("el => el.classList.contains('dark-theme')")
    assert body_class_dark, "Dark-theme class not applied to body!"
    print("Dark Theme activation verified.")

    await theme_button.click()
    await page.wait_for_timeout(500)
    body_class_light = await page.locator("body").evaluate("el => !el.classList.contains('dark-theme')")
    assert body_class_light, "Dark-theme class not removed from body!"
    print("Light Theme restoration verified.")

    # 3. Slow Scroll to trigger animations
    for y in range(0, 4200, 400):
        await page.mouse.wheel(0, 400)
        await page.wait_for_timeout(100)
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(500)

    # Take Fullpage Screenshot
    await page.screenshot(path="verification/screenshots/desktop_complete.png", full_page=True)
    print("Desktop full page screenshot captured.")

async def run_mobile_test(page):
    print("--- [Mobile UI Verification] ---")
    await page.goto("http://127.0.0.1:8000")
    await page.wait_for_timeout(1000)

    # 1. Logo should be hidden on mobile screen < 767px
    nav_logo = page.locator(".nav__logo")
    is_logo_hidden = await nav_logo.evaluate("el => getComputedStyle(el).display === 'none'")
    assert is_logo_hidden, "Logo should be hidden on mobile."
    print("Mobile logo display:none verified.")

    # 2. Test Drawer Open/Close
    nav_toggle = page.locator("#nav-toggle")
    nav_menu = page.locator("#nav-menu")
    nav_close = page.locator("#nav-close")

    await nav_toggle.click(force=True)
    await page.wait_for_timeout(500)
    is_shown = await nav_menu.evaluate("el => el.classList.contains('show-menu')")
    assert is_shown, "Mobile menu should have 'show-menu' class."
    print("Mobile menu open verified.")

    await page.screenshot(path="verification/screenshots/mobile_menu_open.png")

    await nav_close.click()
    await page.wait_for_timeout(500)
    is_hidden = await nav_menu.evaluate("el => !el.classList.contains('show-menu')")
    assert is_hidden, "Mobile menu should not have 'show-menu' class after close."
    print("Mobile menu close verified.")

    # 3. Form Submit in Mobile layout
    await page.locator("#user-name").fill("Siti Aminah")
    await page.locator("#user-email").fill("siti.aminah@yahoo.com")
    await page.locator("#user-project").fill("Saya tertarik dengan program Edukasi & Literasi Teknologi.")
    await page.wait_for_timeout(500)

    submit_btn = page.locator(".contact__button")
    await submit_btn.click()
    print("Contact form submitted on mobile.")

    # Wait for response message
    message_element = page.locator("#contact-message")
    await message_element.wait_for(state="visible")
    await page.wait_for_timeout(2000)

    response_text = await message_element.inner_text()
    print(f"Mobile form response: {response_text}")
    assert "berhasil" in response_text.lower(), "Submission was not simulated successfully."
    print("Mobile form submission and feedback verified.")

    await page.screenshot(path="verification/screenshots/mobile_complete.png", full_page=True)
    print("Mobile full page screenshot captured.")

async def main():
    os.makedirs("verification/screenshots", exist_ok=True)
    os.makedirs("verification/videos", exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Context
        print("Initializing Desktop Context...")
        desktop_context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        desktop_page = await desktop_context.new_page()
        await run_desktop_test(desktop_page)
        await desktop_context.close()

        # 2. Mobile Context
        print("Initializing Mobile Context...")
        mobile_context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            record_video_dir="verification/videos/"
        )
        mobile_page = await mobile_context.new_page()
        await run_mobile_test(mobile_page)
        await mobile_context.close()

        await browser.close()
    print("All UI verifications completed successfully!")

if __name__ == "__main__":
    asyncio.run(main())
