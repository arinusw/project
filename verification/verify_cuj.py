import asyncio
from playwright.async_api import async_playwright

async def record_cuj():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(
            viewport={"width": 1280, "height": 800},
            record_video_dir="verification/videos/"
        )
        page = await context.new_page()
        await page.goto("http://127.0.0.1:8000/index.html")
        await page.wait_for_load_state("networkidle")

        # 1. Scroll through sections
        for section in ["#about", "#services", "#education", "#social", "#contact"]:
            await page.evaluate(f"document.querySelector('{section}').scrollIntoView({{behavior: 'smooth'}})")
            await page.wait_for_timeout(800)

        # 2. Toggle Theme to Dark Mode
        await page.click("#theme-button")
        await page.wait_for_timeout(1000)

        # 3. Fill Contact Form
        await page.fill("#name", "Rian Wijaya")
        await page.fill("#email", "rian@arcreative.id")
        await page.fill("#subject", "Kerjasama Edukasi Digital")
        await page.fill("#message", "Saya berminat untuk mengadakan kolaborasi workshop teknologi bersama Ar Creative.")

        await page.evaluate("""() => {
            const form = document.getElementById('contact-form');
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'simulation_mode';
            hidden.value = '1';
            form.appendChild(hidden);
        }""")

        await page.click("#contact-form button[type='submit']")
        await page.wait_for_selector(".contact__message.success")
        await page.wait_for_timeout(1000)

        # Final Screenshot
        await page.screenshot(path="verification/screenshots/final_cuj.png")

        await context.close()
        await browser.close()
        print("CUJ Video recording completed!")

if __name__ == "__main__":
    asyncio.run(record_cuj())
