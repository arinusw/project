import asyncio
from playwright.async_api import async_playwright

async def run():
    async with async_playwright() as p:
        # Launch browser
        browser = await p.chromium.launch(headless=True)

        # 1. Desktop Verification & CUJ Recording
        context_desktop = await browser.new_context(
            viewport={'width': 1280, 'height': 800},
            record_video_dir='verification/videos/'
        )
        page = await context_desktop.new_page()

        await page.goto('http://127.0.0.1:8000/index.html')
        await page.wait_for_timeout(1000)

        # Capture light theme top screenshot
        await page.screenshot(path='verification/screenshots/desktop_light.png', full_page=False)

        # Toggle theme to dark
        theme_btn = page.locator('#theme-button')
        await theme_btn.click()
        await page.wait_for_timeout(500)
        await page.screenshot(path='verification/screenshots/desktop_dark.png', full_page=False)

        # Scroll through sections to verify and trigger effects
        sections = ['#home', '#about', '#services', '#education', '#contact']
        for sec in sections:
            await page.locator(f'#nav-menu a[href="{sec}"]').click()
            await page.wait_for_timeout(600)

        # Submit contact form in simulation mode
        await page.fill('#name', 'Ahmad Budi')
        await page.fill('#email', 'ahmad@example.com')
        await page.fill('#subject', 'Permintaan Penawaran Website')
        await page.fill('#message', 'Halo Ar Creative, saya tertarik menggunakan jasa pembuatan website untuk bisnis kami.')

        # Inject simulation mode field to form
        await page.evaluate("""
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        """)

        await page.click('button[type="submit"]')
        await page.wait_for_timeout(1000)
        await page.screenshot(path='verification/screenshots/contact_submitted.png', full_page=False)

        await page.screenshot(path='verification/screenshots/final_cuj.png', full_page=True)
        await context_desktop.close()

        # 2. Mobile Verification
        context_mobile = await browser.new_context(
            viewport={'width': 375, 'height': 667},
            user_agent='Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15'
        )
        page_m = await context_mobile.new_page()
        await page_m.goto('http://127.0.0.1:8000/index.html')
        await page_m.wait_for_timeout(1000)
        await page_m.screenshot(path='verification/screenshots/mobile_light.png', full_page=False)

        # Open mobile menu
        await page_m.click('#nav-toggle')
        await page_m.wait_for_timeout(500)
        await page_m.screenshot(path='verification/screenshots/mobile_menu.png', full_page=False)

        # Close menu
        await page_m.click('#nav-close')
        await page_m.wait_for_timeout(300)

        # Toggle dark mode on mobile
        await page_m.click('#theme-button')
        await page_m.wait_for_timeout(500)
        await page_m.screenshot(path='verification/screenshots/mobile_dark.png', full_page=False)

        await context_mobile.close()
        await browser.close()
        print("Verification complete!")

asyncio.run(run())
