!pip install telethon
!pip install qrcode[pil]

from datetime import datetime, timezone
from telethon import TelegramClient
from google.colab import files
from google.colab import ai

import asyncio
import qrcode
import re

api_id = '###'
api_hash = '###'

channels = [
    # сообщества
    "t.me/mom_i_go", # Мам, я в горы, перезвоню…
    "t.me/snowcapper_tour", # Snowcapper tour
    "t.me/topayi", # ıllıllı ТОПАЙ ıllıllı
    "t.me/two_backpacks", # Двое с рюкзаком | Походы и сплавы в Красноярске
    "t.me/yachtkrsk", # Прогулки по Красноярскому водохранилищу 🚤
    # группы
    "t.me/yogajulja", # Поющие Чаши|Йога Сочи
    "t.me/weekendwalks", # Прогулки выходного дня
    "t.me/walkpack", # Пешком с рюкзаком
    "t.me/up_d0wn", # ⬆️ВверхВниз⬇️
    "t.me/turist_jurist", # Дмитрий Федоров. ПОХОДЫ, ПУТЕШЕСТВИЯ, СПЛАВЫ по РЕКАМ
    "t.me/turclub_bs", # турклуб [БС]
    "t.me/troe_i_anyslo", # с тремя под мышкой в городе К.
    "t.me/trikoniaclub", # Клуб ТРИКОНЯ| походы, туризм, активный отдых
    "t.me/tretyakov_foto", # Дикая Сибирь | Wild Siberia
    "t.me/timeonway", # Волна впечатлений
    "t.me/territoryGor", # Территория Гор
    "t.me/tata_69_A", # Походы с Михаилом и Татьяной
    "t.me/stogova_lena", # В путь с Леной Стоговой
    "t.me/sovatravel_blog", # Сова Тревел - путешествия по России
    "t.me/simsimkrsk", # Экскурсии в Красноярске , ТА Сим Сим
    "t.me/shirnind", # Дмитрий Ширнин | Фотография и походы
    "t.me/rhythms_of_the_wandering", # Ритмы Странствий👋
    "t.me/redtrevel", # КРАСНАЯ ШАПКА Приключений 🚩
    "t.me/phytodoctor01", # 🌺Травница доктор Мила Гаращенко
    "t.me/photo_travelers", # Мои душевные фотоприключения
    "t.me/nomad124ru", # 🏕 Сибирские кочевники & Денис Самсонов
    "t.me/neposedy_travel", # Летние закаты и ночевки
    "t.me/my_ge0graphy", # МОЯ ГЕОГРАФИЯ
    "t.me/marussiatravel", # MARUSSIA TRAVEL
    "t.me/ligastrannikov", # Лига Странников
    "t.me/lentavremeni_travel", # Лента времени| Фототуры с Ольгой Пех
    "t.me/lbsib_channel", # 🔷 Лидеры Будущего 🔶 Развитие детей
    "t.me/krasspeleo", # КККС - Новости
    "t.me/krashiking", # Красноярский Хайкинг
    "t.me/krasalpchat", # Красноярский клуб альпинистов
    "t.me/kraevushka_life", # Красноярская краевая научная библиотека
    "t.me/kolesimpokrau", # Visitsiberia
    "t.me/klubLarinaKrasnoyarsk", # Экскурсии, походы «Пути и Шествия» I 🧡 Турагенство Красноярск!
    "t.me/julia_stefanovskaja", # Точка Движения
    "t.me/izitour_ru", # IZITOUR.RU ПОХОДЫ
    "t.me/hodinazdorove", # ХОДИ НА ЗДОРОВЬЕ💚
    "t.me/hardtorest", # турклуб «Отдыхать трудно»|туризм, походы в Красноярске
    "t.me/gremgriva", # Эко-парк «Гремячая грива»
    "t.me/fototestdrive", # ОЛЕСАЙКА
    "t.me/familyclub_vmeste", # 👨‍👩‍👧‍👦ВМЕСТЕ👨‍👩‍👧‍👦 Семейный клуб
    "t.me/expertvisitsiberia", # Эксперт.VisitSiberia 💙
    "t.me/excursion24", # 🌐 Экскурсии Красноярск
    "t.me/erebor24", # Ходим в горы 🙌🏻🏔👩‍👧‍👦
    "t.me/endeavour_channel", # Endeavour Tour | Эндевор Тур
    "t.me/elenaracprekrasnaya", # С РЮКЗАЧКОМ НАЛЕГКЕ!
    "t.me/elenabogoslivets", # Гулять Так Гулять
    "t.me/dvizhzh", # Анонсы походов
    "t.me/dvizh_turizm", # ДВИЖЖЖ
    "t.me/duh_nomad", # Дух Кочевника • Туры на снегоходах в Красноярске • Сплавы по Мане и Енисею • SUP • Байдарки • Морские каяки • Пакрафты • Квизы
    "t.me/doska_sup_club", # Doska SUP club прогулки Красноярск
    "t.me/domik_v_lesy_berloga", # Анонс будущих живых встреч
    "t.me/doidykogdadoidy", # Дойду когда дойду
    "t.me/clubalga24", # Анонсы ПВД (однодневных походов)
    "t.me/city_guide", # Гид в нетуристическом городе
    "t.me/bvs_trips", # Быстрее! Выше! Сильнее! 💪
    "t.me/bobrolog", # Фанпарк «Бобровый лог»
    "t.me/bigtravel365", # Big Travel
    "t.me/bazaar_moms", # Mom’s Bazaar Афиша Красноярск
    "t.me/artsupru", # АНОНСЫ ТУРОВ
    "t.me/archeotour19", # Археотур. Путешествия по Хакасии
    "t.me/YaraLi_tur", # ЯраЛи_тур 🧑‍💼👩‍💻
    "t.me/TripBazaar_Travel", # TripBazaar 🌅 - МАРКЕТПЛЕЙС Путешествий /Событий/ Услуг в КРАСНОЯРСКЕ и ОКРЕСТНОСТЯХ.🌎🌏🌍
    "t.me/SunTpona", # ☀️ SunTpoпa ☀️
    "t.me/SUP_LINER_Mana", # SUP_LINER Туры по Мане, САП Красноярск
    "t.me/R1MTBB2xIYRkN2Ri", # Активные Выходные_365
    "t.me/Nina_baykalova", # Время с пользой
    "t.me/NaXxNcuJfI1iZDEy", # Жанна Travel
    "t.me/Manasup_ko", # Cапы с Мари Андре
    "t.me/Kraj_Sayan", # По Краю Саян
    "t.me/Kayaktrips", # Клуб Стихия⛷🏕✈️🚗🛶
    "t.me/Hochu_I_Hozhu", # Хочу и хожу
    "t.me/GeographyHiking", # 🌍 География 🌍 Походы
    "t.me/ElenaDobroxodz", # Идём с нами)
    "t.me/Back_Pack_Travel", # Бэк Пэк Тревел
    "t.me/Active_Weekend365", # Активные выходные_365
    "t.me/zamanie_tur", # zamanie_tur
    "t.me/airsiberia",
    "t.me/krassea_alliance", # KRASSEA ALLIANCE | ЯХТ ТУРЫ СО СМЫСЛОМ
    "t.me/shirnind",
    "t.me/supturkrsk",
    't.me/clubalga24', # Анонсы ПВД (однодневных походов) # (чат)
    "t.me/photo_trips",  # Фотопоходы- Тропами предков! 👣
    "t.me/mo_tour",  # Своя тропа * Походы * Путешествия * Приключения
    "t.me/geoturizm",  # Геотуризм - походы и экскурсии Красноярск
    "t.me/mystolby",  # Мои Столбы
    "t.me/na_opyte_tur",  # На опыте! Cнаряжение и туризм. Акции, скидки, рекомендации.
    "t.me/umberi_travel",  # Клуб путешественников УмБери 🌏
]

client = TelegramClient('getContent', api_id, api_hash)

async def main():

    # установка соединения
    await client.connect()

    #  авторизация
    if not await client.is_user_authorized():
        print("Генерируем QR-код...")
        qr_login = await client.qr_login()

        qr = qrcode.QRCode()
        qr.add_data(qr_login.url)
        img = qr.make_image()
        img.save('qr_code.png')

        files.download('qr_code.png')
        print("QR-код скачан. Отсканируйте его в Telegram")

        try:
            await qr_login.wait(60)
            print("Успешная авторизация!")
        except:
            print("Время вышло, запустите снова")
            await client.disconnect()
            return

    # --- блок сбора постов
    posts = []

    for channel in channels:
        messages = await client.get_messages(channel, limit=10)

        for message in messages:
            messageDate = message.date
            compareDate = datetime(2026, 2, 19, 17, 0, tzinfo=timezone.utc)

            if messageDate >= compareDate:
                if message.post and message.text:
                    posts.append({
                        "date": f"{messageDate}",
                        "channel": channel,
                        "channel_id": message.peer_id.channel_id,
                        "post_id": message.id,
                        "link": f"https://t.me/{channel}/{message.id}",
                        "content": message.text,
                    })

    print(posts)
    # --- блок сбора постов

    # закрытие соединение после сбора данных
    await client.disconnect()

    return posts

posts = await main()

# todo: пересмотреть минус-слова в промпте
prompt = f"""Проанализируй контент каждого поста и верни json-коллекцию анонсов туристических мероприятий.

Анонсом туристического мероприятия можно считать контент, в котором есть информация о:
1. дате начала мероприятия;
2. время начала мероприятия;
3. время и дата больше текущего времени и даты.

Элемент коллекции должен иметь следующую структуру:
 - title - заголовок (название мероприятия),
 - description - краткое описание мероприятия,
 - date_start - дата начала мероприятия,
 - time_start - время начала мероприятия,
 - price_min - минимальная стоимость за участие в мероприятии (если о стоимости не говорится, то 0)
 - price_max - максимальная стоимость за участие в мероприятии (если о стоимости не говорится, то 0)
 - category - отнеси анонс к одной из категорий (походы, сплавы, экскурсии, спелео, восхождения, туры, соревнования, фото)
 - child - true - если в анонсе есть информация о том, что на мероприятие можно с детьми, false - если информации о детях нет

Оставь без изменения параметры:
 - date;
 - channel;
 - channel_id;
 - post_id;
 - link.

Если пост не является анонсом, то переходи к обработке следующего элемента.

Пост не является анонсом если он представляет из себя:
 - информацию о предоставлении услуг(и);
 - отчёт о том, как прошло мероприятие;
 - отзыв о том, как прошло мероприятие;
 - иную информацию без указания даты и времени начала мероприятия.

Пост не является туристическим мероприятием если:
 - текст о мастер-классе;
 - текст о спектакле;
 - текст о культуре и посещении музеев;
 - текст о науке или рукоделии;
 - текст о встрече в квартире;
 - текст о нетворкинге;
 - текст о философии;
 - текст о церимонии;
 - текст о психологии;

Справка по категориям:
 - походы - не сложный маршрут, похожий на прогулку, может быть с подъёмом на гору, подготовка не требуется, традиционное мероприятие, обычно на один день или несколько часов;
 - восхождение - серьезное мероприятие, текст содержит упоминание о том, какая высота будет набрана и как называется гора (Аргыджэк, Борус и другие вершины), если такой информации нет, то это поход;
 - экскурсии - в тексте содержится слово экскурсия;
 - спелео - это спуски в пещеры;
 - туры - это мероприятие продолжительностью в несколько дней, смысл которого посетить новые места группой, часто на транспорте или заказном автобусе (тур по Алтаю, в Лесосибирск, тур на Иванвские озёра, путешествие в Тыву);
 - сплавы - в таком посте предлагается сплавиться по реке на сапах, байдарках, пакрафтах;
 - соревнования - в тексте фигурирует слово соревнования, а также обозначается их специфика;
 - фото - текст с акцентом на фотографию достопримечательностей, живописных мест или участников похода или прогулки.

Список постов:
{posts}"""
response = ai.generate_text(prompt, model_name='google/gemini-2.5-flash-lite')
print(response)

"""## Link parser"""

links = []

for channel in channels:
    messages = await client.get_messages(channel, limit=3000)

    for message in messages:

        url_pattern = r"https?://(?:[-\w.]|(?:%[\da-fA-F]{2}))+(?:/[-\w.%?=&#+]*)*"

        if message and message.text:

            found_urls = re.findall(url_pattern, message.text)

            for url in found_urls:

                if url.startswith("https://t.me/krasoutdoor") or url.startswith("https://vk.com") or url.startswith("https://vk.ru") or url.startswith("https://vk.cc"):
                    continue

                if url not in links:
                    links.append(url)

links.sort()

print(str(links))