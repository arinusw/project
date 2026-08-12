import asyncio
from playwright.async_api import async_playwright

async def record_cuj():
    print("Recording complete CUJ video...")
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="/home/jules/verification/videos/"
        )
        page = await context.new_page()
        await page.goto("http://127.0.0.1:8000/index.html")
        await page.wait_for_load_state("networkidle")

        # Incremental scroll down to trigger all animations
        for scroll_offset in range(0, 4000, 500):
            await page.mouse.wheel(0, 500)
            await asyncio.sleep(0.3)

        # Scroll back up
        await page.evaluate("window.scrollTo(0, 0)")
        await asyncio.sleep(0.5)

        # Click menu items to showcase transition
        about_link = page.locator("#nav-menu a[href='#about']")
        await about_link.click()
        await asyncio.sleep(1)

        # Toggle theme
        theme_button = page.locator("#theme-button")
        await theme_button.click()
        await asyncio.sleep(1)

        # Fill & submit form under simulation
        await page.fill("#user_name", "Arinus Wijaya")
        await page.fill("#user_email", "arinus@arcreative.com")
        await page.fill("#user_project", "Ingin membuat landing page profil sekolah yang interaktif dan responsive.")
        await page.evaluate("""
            const form = document.getElementById('contact-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'simulation_mode';
            input.value = '1';
            form.appendChild(input);
        """)

        await page.locator("#contact-form button[type='submit']").click()
        await page.locator("#contact-message").wait_for(state="visible")
        await asyncio.sleep(1)

        await context.close()

        # Rename output video to standard name
        import os
        video_path = await page.video.path()
        os.rename(video_path, "/home/jules/verification/videos/cuj_journey.webm")
        print("CUJ video saved to /home/jules/verification/videos/cuj_journey.webm")
        await browser.close()

if __name__ == "__main__":
    asyncio.run(record_cuj())
