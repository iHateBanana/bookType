
export const fetchBooks = async (query = 'science') => {
    try {
        const response = await fetch(`https://gutendex.com/books/?search=${query}`);
        const data = await response.json();
        return data.results;
    } catch (error) {
        console.error('Error fetching books:', error);
    }
};
