const CuisineFilter = ({ cuisines, onFilterChange, selectedCuisine }) => {
  const handleFilterChange = (e) => {
    const value = e.target.value;
    onFilterChange(value);
  };

  return (
    <div className="cuisine-filter mb-4">
      <label htmlFor="cuisine" className="block text-sm font-medium text-gray-700 dark:text-gray-200">
        Filter by Cuisine
      </label>
      <select
        id="cuisine"
        value={selectedCuisine}
        onChange={handleFilterChange}
        className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
      >
        <option value="">All Cuisines</option>
        {cuisines.map((cuisine) => (
          <option key={cuisine.slug} value={cuisine.name}>
            {cuisine.name} ({cuisine.set_menus_count} menus)
          </option>
        ))}
      </select>
    </div>
  );
};

export default CuisineFilter; 