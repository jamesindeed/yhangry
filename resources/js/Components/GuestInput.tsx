import { useState } from 'react';

const GuestInput = ({ onChange }) => {
  const [guests, setGuests] = useState(1);

  const handleInputChange = (e) => {
    const value = parseInt(e.target.value) || 1;
    setGuests(value);
    onChange(value);
  };

  return (
    <div className="mb-4">
      <label htmlFor="guests" className="block text-sm font-medium text-gray-700 dark:text-gray-200">
        Number of Guests
      </label>
      <input
        type="number"
        id="guests"
        value={guests}
        onChange={handleInputChange}
        min="1"
        className="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
      />
    </div>
  );
};

export default GuestInput; 