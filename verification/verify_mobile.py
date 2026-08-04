import asyncio
import os
from playwright.async_api import async_playwright

async def verify_mobile():
    print("Starting Mobile Verification...")
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        # Mobile viewport size
        context = await browser.new_context(viewport={"width": 375, "height": 667})
        page = await context.new_page()

        await page.goto("http://127.0.0.1:8000")
        await page.wait_for_timeout(2000)

        # 1. Verify navigation brand logo is hidden on mobile screen
        nav_logo = page.locator(".nav__logo")
        is_logo_hidden = await nav_logo.evaluate("el => getComputedStyle(el).display === 'none'")
        print(f"Brand logo is correctly hidden on mobile viewport (< 767px): {is_logo_hidden}")

        # 2. Test Mobile drawer toggle open
        nav_toggle = page.locator("#nav-toggle")
        nav_menu = page.locator("#nav-menu")

        # Open mobile drawer using force=True to bypass ScrollReveal transition overlays
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(500)

        is_menu_shown = await nav_menu.evaluate("el => el.classList.contains('show-menu')")
        print(f"Mobile menu drawer is shown: {is_menu_shown}")

        os.makedirs("verification/screenshots", exist_ok=True)
        await page.screenshot(path="verification/screenshots/mobile_menu_open.png")
        print("Mobile menu open screenshot captured.")

        # 3. Test Mobile drawer toggle close via close button
        nav_close = page.locator("#nav-close")
        await nav_close.click()
        await page.wait_for_timeout(500)

        is_menu_hidden = await nav_menu.evaluate("el => !el.classList.contains('show-menu')")
        print(f"Mobile menu drawer is successfully closed: {is_menu_hidden}")

        # 4. Open drawer again and test navigation link clicks
        await nav_toggle.click(force=True)
        await page.wait_for_timeout(300)

        # Use #nav-menu scoped selector to avoid ambiguity
        about_link = page.locator("#nav-menu a[href*='about']")
        await about_link.click(force=True)
        await page.wait_for_timeout(500)

        is_menu_closed_by_link = await nav_menu.evaluate("el => !el.classList.contains('show-menu')")
        print(f"Clicking menu link closed the drawer: {is_menu_closed_by_link}")

        # 5. Perform incremental scrolling
        print("Performing incremental scrolling on mobile...")
        for y in range(0, 4500, 350):
            await page.mouse.wheel(0, 350)
            await page.wait_for_timeout(100)

        await page.evaluate("window.scrollTo(0, 0)")
        await page.wait_for_timeout(500)

        # 6. Take full-page screenshot
        await page.screenshot(path="verification/screenshots/mobile_fullpage.png", full_page=True)
        print("Mobile full page screenshot captured.")

        await browser.close()
    print("Mobile Verification Completed Successfully!")

if __name__ == "__main__":
    asyncio.run(verify_mobile())
