import asyncio
from playwright.async_api import async_playwright

async def trigger_scroll_animations(page):
    sections = ['#home', '#about', '#services', '#education', '#social', '#contact']
    for section in sections:
        await page.evaluate(f"document.querySelector('{section}')?.scrollIntoView({{behavior: 'smooth'}})")
        await page.wait_for_timeout(300)
    await page.evaluate("window.scrollTo({top: 0, behavior: 'instant'})")
    await page.wait_for_timeout(300)

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()

        # 1. Desktop Test (1280x800)
        page = await browser.new_page(viewport={'width': 1280, 'height': 800})
        await page.goto('http://127.0.0.1:8000/?simulation_mode=1')
        await page.wait_for_timeout(500)

        await trigger_scroll_animations(page)
        await page.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Toggle theme to dark mode
        await page.click('#theme-button')
        await page.wait_for_timeout(300)
        await page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        # Contact form submission test
        await page.evaluate("document.querySelector('#contact')?.scrollIntoView({behavior: 'instant'})")
        await page.fill('#contact-name', 'Budi Santoso')
        await page.fill('#contact-email', 'budi@example.com')
        await page.fill('#contact-subject', 'Pengembangan Website')
        await page.fill('#contact-message', 'Halo Ar Creative, saya tertarik menggunakan jasa pembuatan website.')
        await page.click('#contact-form button[type="submit"]')

        # Wait for response message
        await page.wait_for_selector('#contact-response-message.success', state='visible')
        await page.screenshot(path='verification/screenshots/desktop_contact_success.png', full_page=False)

        await page.close()

        # 2. Mobile Test (375x667)
        mobile_context = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            is_mobile=True
        )
        mobile_page = await mobile_context.new_page()
        await mobile_page.goto('http://127.0.0.1:8000/?simulation_mode=1')
        await mobile_page.wait_for_timeout(500)

        await trigger_scroll_animations(mobile_page)
        await mobile_page.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)

        # Test mobile menu toggle with force=True
        await mobile_page.click('#nav-toggle', force=True)
        await mobile_page.locator('#nav-menu').wait_for(state='visible')
        await mobile_page.wait_for_timeout(300)
        await mobile_page.screenshot(path='verification/screenshots/mobile_menu_open.png', full_page=False)

        await mobile_page.click('#nav-close', force=True)
        await mobile_page.wait_for_timeout(300)

        await mobile_context.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(main())
