import requests
import beutifulsoup4

def GetPlateInfo(plate):
    url = "https://opendata.rdw.nl/resource/m9d7-ebf2.json?kenteken=" + plate
    response = requests.get(url)
    soup = BeautifulSoup(response.text, "html.parser")
    return soup