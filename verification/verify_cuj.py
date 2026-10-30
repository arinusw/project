import asyncio
from playwright.async_api import async_playwright

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context.new_page()

        # Navigate
        await page.goto('http://127.0.0.1:8000/?simulation_mode=1')
        await page.wait_for_timeout(1000)

        # Scroll through sections
        sections = ['#home', '#about', '#services', '#education', '#social', '#contact']
        for section in sections:
            await page.evaluate(f"document.querySelector('{section}')?.scrollIntoView({{behavior: 'smooth'}})")
            await page.wait_for_timeout(1000)

        # Theme toggle test
        await page.click('#theme-button')
        await page.wait_for_timeout(1000)
        await page.click('#theme-button')
        await page.wait_for_timeout(1000)

        # Fill form
        await page.fill('#contact-name', 'Aris Nuswantoro')
        await page.fill('#contact-email', 'aris@arcreative.id')
        await page.fill('#contact-subject', 'Permintaan Layanan Digital')
        await page.fill('#contact-message', 'Halo Ar Creative, kami berminat untuk melakukan kolaborasi pengembangan produk digital.')
        await page.wait_for_timeout(1000)
        await page.click('#contact-form button[type="submit"]')
        await page.wait_for_selector('#contact-response-message.success', state='visible')
        await page.wait_for_timeout(1500)

        await context.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(main())
