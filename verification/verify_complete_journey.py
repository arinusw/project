import time
import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    # Scroll down step-by-step to trigger ScrollReveal animations
    for i in range(1, 10):
        await page.mouse.wheel(0, 400)
        await asyncio.sleep(0.3)
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await asyncio.sleep(0.5)

async def run_desktop_verification(browser):
    context = await browser.new_context(viewport={"width": 1280, "height": 800})
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000")
    await page.wait_for_selector("#home")

    await trigger_scroll_animations(page)
    await page.screenshot(path="verification/screenshots/desktop_light.png", full_page=False)

    # Toggle dark theme
    theme_btn = page.locator("#theme-button")
    await theme_btn.click()
    await asyncio.sleep(0.5)
    await page.screenshot(path="verification/screenshots/desktop_dark.png", full_page=False)

    # Test contact form submission with simulation_mode
    await page.evaluate("window.scrollTo({top: document.body.scrollHeight, behavior: 'instant'})")
    await asyncio.sleep(0.5)

    await page.fill("#name", "Pengguna Uji")
    await page.fill("#email", "test@example.com")
    await page.fill("#message", "Halo Ar Creative, ini pesan pengujian otomatis.")

    # Inject hidden simulation_mode input to test contact.php
    await page.evaluate("""() => {
        const form = document.getElementById('contact-form');
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'simulation_mode';
        hidden.value = '1';
        form.appendChild(hidden);
    }""")

    await page.click("#contact-form button[type='submit']")
    await page.wait_for_selector("#contact-message:has-text('Terima kasih')", timeout=5000)
    await page.screenshot(path="verification/screenshots/desktop_contact_success.png", full_page=False)

    await context.close()

async def run_mobile_verification(browser):
    context = await browser.new_context(viewport={"width": 375, "height": 667})
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000")
    await page.wait_for_selector("#home")

    await page.screenshot(path="verification/screenshots/mobile_light.png", full_page=False)

    # Open mobile menu using click on nav-toggle
    nav_toggle = page.locator("#nav-toggle")
    await nav_toggle.click(force=True)
    await page.wait_for_selector("#nav-menu.show-menu")
    await asyncio.sleep(0.5) # Wait for slide transition
    await page.screenshot(path="verification/screenshots/mobile_menu_open.png", full_page=False)

    # Close mobile menu
    await page.evaluate("document.getElementById('nav-close').click()")
    await asyncio.sleep(0.5)

    # Navigate to contact section via hash
    await page.goto("http://127.0.0.1:8000/#contact")
    await asyncio.sleep(0.5)
    await page.screenshot(path="verification/screenshots/mobile_contact.png", full_page=False)

    await context.close()

async def run_cuj_video(browser):
    context = await browser.new_context(
        viewport={"width": 1280, "height": 800},
        record_video_dir="verification/videos/"
    )
    page = await context.new_page()
    await page.goto("http://127.0.0.1:8000")
    await page.wait_for_selector("#home")

    # Hero section
    await asyncio.sleep(1)

    # Scroll through About, Services, Education, Social, Contact
    sections = ["#about", "#services", "#education", "#social", "#contact"]
    for sec in sections:
        await page.goto(f"http://127.0.0.1:8000/{sec}")
        await asyncio.sleep(1)

    # Toggle dark theme
    await page.click("#theme-button")
    await asyncio.sleep(1)

    # Submit form
    await page.fill("#name", "Pengunjung Studio")
    await page.fill("#email", "pengunjung@domain.com")
    await page.fill("#message", "Saya tertarik menggunakan jasa Ar Creative.")
    await page.evaluate("""() => {
        const form = document.getElementById('contact-form');
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'simulation_mode';
        hidden.value = '1';
        form.appendChild(hidden);
    }""")
    await page.click("#contact-form button[type='submit']")
    await page.wait_for_selector("#contact-message:has-text('Terima kasih')", timeout=5000)
    await asyncio.sleep(1)

    await page.screenshot(path="verification/screenshots/final_cuj.png", full_page=False)
    await context.close()

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        print("Running desktop verification...")
        await run_desktop_verification(browser)
        print("Running mobile verification...")
        await run_mobile_verification(browser)
        print("Running CUJ video recording...")
        await run_cuj_video(browser)
        await browser.close()
    print("Verification completed successfully!")

if __name__ == "__main__":
    asyncio.run(main())
