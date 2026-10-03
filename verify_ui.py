import subprocess
import sys

def run_tests():
    print("--- Running Desktop Verification ---")
    res1 = subprocess.run([sys.executable, "verification/verify_desktop.py"])
    if res1.returncode != 0:
        print("Desktop verification failed!")
        sys.exit(1)

    print("--- Running Mobile Verification ---")
    res2 = subprocess.run([sys.executable, "verification/verify_mobile.py"])
    if res2.returncode != 0:
        print("Mobile verification failed!")
        sys.exit(1)

    print("--- Running Complete Journey & Form Verification ---")
    res3 = subprocess.run([sys.executable, "verification/verify_complete_journey.py"])
    if res3.returncode != 0:
        print("Complete journey verification failed!")
        sys.exit(1)

    print("All UI verification scripts executed successfully.")

if __name__ == "__main__":
    run_tests()
