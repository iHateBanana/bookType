
export async function fetchBooks(query = '') {
    try {
        const response = await fetch(`https://gutendex.com/books?search=${encodeURIComponent(query)}`);
        const data = await response.json();

        return data.results
            .filter(book => !book.copyright) // Only public domain
            .map(book => ({
                id: book.id,
                title: book.title,
                author: book.authors?.[0]?.name || 'Unknown',
                cover_url: book.formats['image/jpeg'] || '/images/default-cover.jpg',
            }));
    } catch (error) {
        console.error('Failed to fetch books:', error);
        return [];
    }
}
