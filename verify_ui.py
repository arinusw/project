import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll through page to trigger ScrollReveal animations
    for _ in range(5):
        await page.mouse.wheel(0, 800)
        await page.wait_for_timeout(300)
    await page.evaluate("window.scrollTo(0, 0)")
    await page.wait_for_timeout(500)

async def run_desktop_test(playwright):
    browser = await playwright.chromium.launch()
    context = await browser.new_context(
        viewport={'width': 1280, 'height': 800},
        record_video_dir="verification/videos/"
    )
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000/")
    await page.wait_for_load_state("networkidle")

    # Trigger entrance animations
    await trigger_scroll_animations(page)

    # Capture light mode desktop screenshot
    await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=True)
    print("Desktop light screenshot saved.")

    # Toggle Dark Mode
    theme_btn = page.locator("#theme-button")
    await theme_btn.click()
    await page.wait_for_timeout(500)
    await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=True)
    print("Desktop dark screenshot saved.")

    # Fill and submit contact form in simulation mode
    await page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
    await page.wait_for_timeout(500)
    await page.fill("#user_name", "Budi Santoso")
    await page.fill("#user_email", "budi@example.com")
    await page.fill("#user_project", "Pengembangan website portofolio studio kreatif.")

    # Append simulation mode hidden field or send via JS dispatch
    await page.evaluate("""() => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'simulation_mode';
        input.value = '1';
        document.getElementById('contact-form').appendChild(input);
    }""")

    await page.click("#contact-form button[type='submit']")
    await page.wait_for_selector("#contact-message:has-text('Pesan Anda berhasil dikirim')")
    print("Form submission simulation verified.")

    await context.close()
    await browser.close()

async def run_mobile_test(playwright):
    browser = await playwright.chromium.launch()
    context = await browser.new_context(
        viewport={'width': 375, 'height': 667},
        is_mobile=True
    )
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000/")
    await page.wait_for_load_state("networkidle")

    # Trigger entrance animations
    await trigger_scroll_animations(page)

    # Capture mobile home screenshot
    await page.screenshot(path="verification/screenshots/mobile_light.png", full_page=True)

    # Open Mobile Navigation Menu
    nav_toggle = page.locator("#nav-toggle")
    await nav_toggle.click(force=True)
    await page.wait_for_timeout(500)
    await page.screenshot(path="verification/screenshots/mobile_menu.png")
    print("Mobile menu screenshot saved.")

    # Close Mobile Menu
    nav_close = page.locator("#nav-close")
    await nav_close.click(force=True)
    await page.wait_for_timeout(300)

    await context.close()
    await browser.close()

async def main():
    async with async_playwright() as playwright:
        await run_desktop_test(playwright)
        await run_mobile_test(playwright)

if __name__ == "__main__":
    asyncio.run(main())
