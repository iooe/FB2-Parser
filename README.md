
# FB2Parser - Simple FB2 to HTML

FB2Parser is a PHP parser for fb2 books.

### Getting Started
1. `composer require tizis/fb2-parser`
2. See example

## Features
- Information Parsing
    - Authors
    - Translators
    - Genres
    - Keywords
    - ...
- Images Parsing
- Content Parsing
- Conversion of notes
- 
## Public Accessors 
FB2Controller-> ...
| Name | desc |
|--|--|
| withNotes() |  parse with notes, else notes|will be deleted
| withImages(['directory' => ... , 'imagesWebPath' => ...] |  parse with images, else images|will be deleted
| startParse()|  start parsing
| getBook() |  return Book object
--------
getBook()-> ...
| Book | desc |
|--|--|
| getAuthors() |  **return** array of Author objects
| getTranslators() |  **return** array of Translator objects
| getInfo() |  **return** BookInfo object
| getChapters() |  **return** array of Chapters objects
--------
getAuthors()[$key]-> ...
| Book | desc |
|--|--|
| getFirstName() |  **return** first name of the author
| getLastName() |  **return** last name of the author
| getFullName() |  **return** full name of the author
--------
getTranslators()[$key]-> ...
| Book | desc |
|--|--|
| getFirstName() |  **return** first name of the translator
| getMiddleName() |  **return** middle name of the translator
| getLastName() |  **return** last name of the translator
| getFullName() |  **return** full name of the translator
| getNickName() |  **return** nickname of the translator
| getEmail() |  **return** email of the translator
--------
getInfo()-> ...
| Book | desc |
|--|--|
| getTitle() |  **return** title of the book
| getAnnotation() |  **return** annotation name of the book
| getGenres() |  **return** array of genres of the book
| getKeywords() |  **return** keywords of the book
| getLang() |  **return** array of lang of the book
--------
getChapters()[$key]-> ...
| Book | desc |
|--|--|
| getTitle() |  **return** title of the chapter
| getContent() |  **return** content of the chapter