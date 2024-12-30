const SetMenuList = ({ menus, guests }: { menus: any[]; guests: number }) => {
  const calculateTotalPrice = (pricePerPerson: number, minSpend: number) => {
    const totalPrice = pricePerPerson * guests;
    return Math.max(totalPrice, minSpend);
  };

  return (
    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
      {menus.map((menu, index) => {
        const totalPrice = calculateTotalPrice(menu.price, menu.minSpend);

        return (
          <div key={index} className="flex h-full flex-col rounded-lg bg-gray-700 p-4 shadow">
            {menu.thumbnail && (
              <img
                src={menu.thumbnail}
                alt={menu.name}
                className="mb-4 h-48 w-full rounded object-cover"
              />
            )}

            <div className="flex-1">
              <h2 className="mb-2 text-xl font-bold text-white">{menu.name}</h2>
              <p className="mb-4 text-gray-300">{menu.description}</p>

              <div className="mb-4 flex flex-wrap gap-2">
                {menu.cuisines.map((cuisine: any, idx: number) => (
                  <span
                    key={idx}
                    className="rounded-full bg-gray-600 px-2 py-1 text-sm text-gray-200"
                  >
                    {cuisine.name}
                  </span>
                ))}
              </div>
            </div>

            <div className="mt-auto border-t border-gray-600 pt-4">
              <div className="mb-1 flex justify-between text-gray-300">
                <span>Price per person</span>
                <span>£{menu.price}</span>
              </div>
              <div className="mb-1 flex justify-between text-gray-300">
                <span>Minimum spend</span>
                <span>£{menu.minSpend}</span>
              </div>
              <div className="flex justify-between font-bold text-white">
                <span>Total for {guests} guests</span>
                <span>
                  £{totalPrice}
                  {totalPrice === menu.minSpend && (
                    <span className="ml-2 text-sm text-gray-400">(min. spend)</span>
                  )}
                </span>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
};

export default SetMenuList;
