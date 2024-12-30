import CuisineFilter from '@/Components/CuisineFilter';
import GuestInput from '@/Components/GuestInput';
import SetMenuList from '@/Components/SetMenuList';
import ShowMoreButton from '@/Components/ShowMoreButton';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { appendMenus, setLoading, setMenus } from '@/store/menuSlice';
import { RootState } from '@/store/store';
import { Head, router } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';

type Props = {
  setMenuData: {
    filters: { cuisines: Array<{ name: string; slug: string; set_menus_count: number }> };
    setMenus: {
      data: any[];
      current_page: number;
      last_page: number;
    };
  };
  cuisineSlug?: string;
};

export default function Dashboard({ setMenuData, cuisineSlug }: Props) {
  const dispatch = useDispatch();
  const {
    items: menus,
    loading,
    currentPage,
    lastPage,
  } = useSelector((state: RootState) => state.menus);
  const [guests, setGuests] = useState(2);

  useEffect(() => {
    dispatch(
      setMenus({
        data: setMenuData.setMenus.data,
        currentPage: setMenuData.setMenus.current_page,
        lastPage: setMenuData.setMenus.last_page,
      })
    );
  }, [setMenuData.setMenus]);

  const loadMore = async () => {
    if (loading) return;
    dispatch(setLoading(true));

    try {
      const response = await axios.get(
        `/api/set-menus?page=${currentPage + 1}${cuisineSlug ? `&cuisineSlug=${cuisineSlug}` : ''}`
      );
      dispatch(
        appendMenus({
          data: response.data.setMenus.data,
          currentPage: response.data.setMenus.current_page,
          lastPage: response.data.setMenus.last_page,
        })
      );
    } finally {
      dispatch(setLoading(false));
    }
  };

  return (
    <AuthenticatedLayout
      header={<h2 className="text-xl font-semibold text-gray-200">Set Menus</h2>}
    >
      <Head title="Dashboard" />

      <div className="py-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div className="rounded-lg bg-gray-800 p-4 shadow-sm">
            <div className="mb-6">
              <GuestInput onChange={setGuests} />
              <CuisineFilter
                cuisines={setMenuData.filters.cuisines}
                selectedCuisine={cuisineSlug || ''}
                onFilterChange={(cuisine: string) =>
                  router.visit('/dashboard' + (cuisine ? `?cuisineSlug=${cuisine}` : ''))
                }
              />
            </div>

            <SetMenuList menus={menus} guests={guests} />

            {currentPage < lastPage && <ShowMoreButton onClick={loadMore} hasMore={!loading} />}

            {loading && (
              <div className="mt-4 text-center">
                <div className="h-6 w-6 animate-spin rounded-full border-4 border-indigo-500 border-r-transparent" />
              </div>
            )}
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
