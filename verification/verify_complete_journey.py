import asyncio
import os
from playwright.async_api import async_playwright

async def run_journey():
    os.makedirs('verification/videos', exist_ok=True)
    os.makedirs('verification/screenshots', exist_ok=True)

    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        context = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context.new_page()

        # Step 1: Visit homepage
        await page.goto('http://127.0.0.1:8000')
        await page.wait_for_timeout(1000)

        # Step 2: Smooth scroll through sections
        sections = ['#about', '#services', '#education', '#social', '#contact']
        for sec in sections:
            await page.evaluate(f"document.querySelector('{sec}').scrollIntoView({{behavior: 'smooth'}})")
            await page.wait_for_timeout(800)

        # Step 3: Toggle dark mode and light mode
        theme_btn = page.locator('#theme-button')
        await theme_btn.click()
        await page.wait_for_timeout(1000)
        await theme_btn.click()
        await page.wait_for_timeout(1000)

        # Step 4: Submit Contact Form
        await page.evaluate("document.querySelector('#contact').scrollIntoView({behavior: 'smooth'})")
        await page.wait_for_timeout(500)

        await page.fill('#name', 'Ananda Putri')
        await page.fill('#email', 'ananda@creative.id')
        await page.fill('#subject', 'Kerjasama Modul Edukasi')
        await page.fill('#message', 'Halo Ar Creative, kami bermaksud mengadakan workshop edukasi teknologi bersama.')

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const simInput = document.createElement('input');
            simInput.type = 'hidden';
            simInput.name = 'simulation_mode';
            simInput.value = '1';
            form.appendChild(simInput);
        }""")

        await page.click('.contact__submit-btn')
        await page.wait_for_selector('#contact-message:has-text("Pesan Anda berhasil terkirim")')
        await page.wait_for_timeout(1500)

        await context.close()
        await browser.close()
        print("Complete customer journey video recorded successfully!")

if __name__ == '__main__':
    asyncio.run(run_journey())
