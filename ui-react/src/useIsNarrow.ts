import { useEffect, useState } from 'react'

/** Single source of truth for responsive layout decisions — never a CSS media query (see
 * melis-react-mobile-responsive skill: `sm:`-style breakpoint classes have proven unreliable
 * in this codebase's bricks). */
export function useIsNarrow(breakpoint = 640): boolean {
  const [narrow, setNarrow] = useState(() => window.innerWidth < breakpoint)
  useEffect(() => {
    const onResize = () => setNarrow(window.innerWidth < breakpoint)
    window.addEventListener('resize', onResize)
    return () => window.removeEventListener('resize', onResize)
  }, [breakpoint])
  return narrow
}
