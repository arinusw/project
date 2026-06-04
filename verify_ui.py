import asyncio
from playwright.async_api import async_playwright
import os
import time

async def capture_screenshots():
    async with async_playwright() as p:
        browser = await p.chromium.launch()
        context = await browser.new_context(viewport={'width': 1280, 'height': 800})
        page = await context.new_page()

        # Navigate to the local server
        try:
            await page.goto('http://localhost:8000/ar-creative/index.html')

            # Wait for ScrollReveal animations to start
            await asyncio.sleep(3)

            # Function to scroll and trigger all animations
            await page.evaluate("""
                async () => {
                    await new Promise((resolve) => {
                        let totalHeight = 0;
                        let distance = 100;
                        let timer = setInterval(() => {
                            let scrollHeight = document.body.scrollHeight;
                            window.scrollBy(0, distance);
                            totalHeight += distance;
                            if(totalHeight >= scrollHeight){
                                clearInterval(timer);
                                resolve();
                            }
                        }, 100);
                    });
                }
            """)

            # Wait a bit after scrolling for all animations to finish
            await asyncio.sleep(2)

            # Capture sections
            sections = ['home', 'about', 'services', 'education', 'social', 'contact']
            for section in sections:
                element = await page.query_selector(f'#{section}')
                if element:
                    await element.screenshot(path=f'verification/screenshots/{section}.png')

            # Full page screenshot
            await page.screenshot(path='verification/screenshots/full_page.png', full_page=True)

            print("Screenshots captured successfully.")

        except Exception as e:
            print(f"Error capturing screenshots: {e}")

        await browser.close()

if __name__ == "__main__":
    asyncio.run(capture_screenshots())
