import asyncio
from playwright.async_api import async_playwright

async def run_mobile_verification():
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Mobile viewport size: 375x667
        context = await browser.new_context(
            viewport={"width": 375, "height": 667},
            is_mobile=True,
            has_touch=True,
            record_video_dir="verification/videos"
        )
        page = await context.new_page()

        print("Opening website on Mobile...")
        await page.goto("http://127.0.0.1:8000/index.html")
        await page.wait_for_timeout(1000)

        # Take initial mobile screenshot
        await page.screenshot(path="verification/screenshots/mobile_initial.png", full_page=True)
        print("Saved mobile_initial.png")

        # Test mobile nav drawer toggle with force click (to bypass ScrollReveal overlays)
        print("Opening mobile menu...")
        toggle_btn = page.locator("#nav-toggle")
        await toggle_btn.click(force=True)
        await page.wait_for_timeout(500)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Saved mobile_menu_open.png")

        # Navigate to #services section via mobile menu links
        print("Clicking Services menu item in mobile nav...")
        services_link = page.locator("#nav-menu a[href='#services']")
        await services_link.click(force=True)
        await page.wait_for_timeout(800)

        await page.screenshot(path="verification/screenshots/mobile_scrolled_services.png")
        print("Saved mobile_scrolled_services.png")

        await context.close()
        await browser.close()

if __name__ == "__main__":
    asyncio.run(run_mobile_verification())
