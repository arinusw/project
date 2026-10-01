import asyncio
import os
import sys
from playwright.async_api import async_playwright

async def run_cuj():
    os.makedirs('verification/videos', exist_ok=True)
    os.makedirs('verification/screenshots', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context.new_page()

        # Navigate home
        await page.goto('http://127.0.0.1:8000', wait_until='networkidle')
        await page.wait_for_timeout(1000)

        # Smooth scroll to About section
        await page.click('a[href="#about"]')
        await page.wait_for_timeout(1000)

        # Smooth scroll to Services section
        await page.click('a[href="#services"]')
        await page.wait_for_timeout(1000)

        # Smooth scroll to Education section
        await page.click('a[href="#education"]')
        await page.wait_for_timeout(1000)

        # Toggle Dark Mode
        await page.click('#theme-button')
        await page.wait_for_timeout(1000)

        # Smooth scroll to Contact section
        await page.click('a[href="#contact"]')
        await page.wait_for_timeout(1000)

        # Fill and submit contact form
        await page.fill('#contact-name', 'Rina Wijaya')
        await page.fill('#contact-email', 'rina@example.com')
        await page.fill('#contact-subject', 'Kerjasama Projek Digital')
        await page.fill('#contact-message', 'Salam Ar Creative, kami berminat berdiskusi mengenai proyek pembuatan website studio.')

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        }""")

        await page.click('#contact-form button[type="submit"]')
        await page.wait_for_timeout(1500)

        # Scroll back up using scrollup button
        scrollup = page.locator('#scroll-up')
        if await scrollup.is_visible():
            await scrollup.click()
            await page.wait_for_timeout(1000)

        await page.screenshot(path='verification/screenshots/complete_journey_final.png')
        print("Completed Complete User Journey verification.")

        await context.close()
        await browser.close()

if __name__ == '__main__':
    asyncio.run(run_cuj())
