import asyncio
from playwright.async_api import async_playwright

async def main():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()

        # 1. Navigate to home
        await page.goto("http://127.0.0.1:8000/", wait_until="networkidle")
        await asyncio.sleep(1)

        # 2. Scroll through sections
        for section_id in ["#about", "#services", "#education", "#social", "#contact"]:
            await page.locator(section_id).scroll_into_view_if_needed()
            await asyncio.sleep(1)

        # 3. Toggle theme
        theme_btn = page.locator("#theme-button")
        await theme_btn.click()
        await asyncio.sleep(1)

        # 4. Fill contact form
        await page.fill("#name", "Siti Rahma")
        await page.fill("#email", "siti@example.id")
        await page.fill("#message", "Saya berminat untuk memesan jasa pembuatan website studio kreatif.")

        await page.evaluate("""
            const form = document.getElementById('contact-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'simulation_mode';
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
        """)

        await page.click("#contact-form button[type='submit']")
        await page.wait_for_selector("#contact-message:has-text('Terima kasih')")
        await asyncio.sleep(1)

        # Final screenshot
        await page.screenshot(path="verification/screenshots/final_cuj.png")

        await context.close()
        await browser.close()
        print("Customer User Journey Video recorded successfully!")

if __name__ == "__main__":
    asyncio.run(main())
