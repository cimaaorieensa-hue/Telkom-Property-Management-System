import pypdf
import sys

def extract_pdf_text(pdf_path):
    try:
        reader = pypdf.PdfReader(pdf_path)
        text = ""
        for page_num in range(len(reader.pages)):
            page = reader.pages[page_num]
            text += f"\n--- Page {page_num + 1} ---\n"
            text += page.extract_text()
        return text
    except Exception as e:
        return f"Error: {e}"

if __name__ == "__main__":
    if len(sys.argv) > 1:
        pdf_file = sys.argv[1]
        extracted_text = extract_pdf_text(pdf_file)
        with open("pdf_extracted_text.txt", "w", encoding="utf-8") as f:
            f.write(extracted_text)
        print("Text extracted and saved to pdf_extracted_text.txt")
    else:
        print("Please provide a PDF file path.")
